<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use App\Models\Swipe;
use App\Models\Matched;
use App\Models\Category;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SwipingPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-hand-raised';
    protected static string $view = 'filament.pages.swiping-page';
    protected static ?int $navigationSort = 1;
    
    public static function getNavigationLabel(): string
    {
        return __('discover.navigation_label');
    }
    
    public function getTitle(): string
    {
        return __('discover.page_title');
    }

    public ?array $currentProduct = null;
    public array $productsStack = [];
    public bool $noMoreProducts = false;
    public ?int $selectedCategory = null;

    private const STACK_SIZE = 15;
    private const MIN_STACK_SIZE = 5;
    private const CACHE_TTL = 3600;

    public function mount(): void
    {
        $this->loadProducts();
    }

    public function loadProducts(): void
    {
        try {
            $products = $this->fetchProducts(self::STACK_SIZE);

            if ($products->isEmpty()) {
                $this->setEmptyState();
                return;
            }

            $this->populateStack($products);
        } catch (\Exception $e) {
            $this->handleError(__('discover.errors.load_failed'), $e);
            $this->setEmptyState();
        }
    }

    public function swipeLeft(): void
    {
        if (!$this->currentProduct) {
            return;
        }

        try {
            $this->recordSwipe('left');
            $this->moveToNextProduct();
        } catch (\Exception $e) {
            $this->handleError(__('discover.errors.swipe_failed'), $e);
            $this->moveToNextProduct();
        }
    }

    public function swipeRight(): void
    {
        if (!$this->currentProduct) {
            return;
        }

        try {
            DB::beginTransaction();

            $this->recordSwipe('right');

            $product = Product::find($this->currentProduct['id']);

            if ($product && $this->createMatchIfMutual($product)) {
                $this->notifyMatch();
            }

            DB::commit();
            $this->moveToNextProduct();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleError(__('discover.errors.swipe_failed'), $e);
            $this->moveToNextProduct();
        }
    }

    public function updatedSelectedCategory(): void
    {
        $this->resetState();
        $this->loadProducts();
    }

    public function resetFilters(): void
    {
        $this->selectedCategory = null;
        $this->resetState();
        $this->loadProducts();
    }

    public function getCategoriesProperty()
    {
        return Cache::remember(
            'active_categories',
            self::CACHE_TTL,
            fn() => Category::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'icon'])
        );
    }

    // -------------------------------
    // Helpers
    // -------------------------------

    private function fetchProducts(int $limit): \Illuminate\Database\Eloquent\Collection
    {
        $userId = auth()->id();

        return Product::query()
            ->active()
            ->where('user_id', '!=', $userId)
            ->whereNotIn('id', $this->getSwipedProductIds($userId))
            ->when($this->selectedCategory, fn($q) => $q->where('category_id', $this->selectedCategory))
            ->with(['category:id,name,icon', 'user:id,name'])
            ->select([
                'id', 'title', 'description', 'price',
                'condition', 'location', 'images',
                'category_id', 'user_id', 'created_at'
            ])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    private function getSwipedProductIds(int $userId): \Illuminate\Database\Eloquent\Builder
    {
        return Swipe::query()
            ->where('user_id', $userId)
            ->select('product_id');
    }

    private function populateStack(\Illuminate\Database\Eloquent\Collection $products): void
    {
        $productsArray = $products->map(fn($product) => $this->formatProduct($product))->toArray();

        if (empty($this->currentProduct) && !empty($productsArray)) {
            $this->currentProduct = array_shift($productsArray);
        }

        $this->productsStack = $productsArray;
        $this->noMoreProducts = false;
    }

    private function formatProduct(Product $product): array
    {
        return [
            'id' => $product->id,
            'title' => $product->title,
            'description' => $product->description,
            'price' => $product->price,
            'condition' => $product->condition,
            'location' => $product->location,
            'images' => $this->normalizeImages($product->images),
            'category' => [
                'id' => $product->category?->id,
                'name' => $product->category?->name ?? __('discover.uncategorized'),
                'icon' => $product->category?->icon,
            ],
            'user' => [
                'id' => $product->user?->id,
                'name' => $product->user?->name ?? __('discover.unknown_user'),
            ],
            'created_at' => $product->created_at?->diffForHumans(),
        ];
    }

    private function normalizeImages($images): array
    {
        if (is_array($images)) {
            return $images;
        }

        if (is_string($images)) {
            $decoded = json_decode($images, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    private function recordSwipe(string $direction): void
    {
        Swipe::create([
            'user_id' => auth()->id(),
            'product_id' => $this->currentProduct['id'],
            'direction' => $direction,
        ]);
    }

    private function createMatchIfMutual(Product $product): bool
    {
        $currentUserId = auth()->id();
        $sellerId = $product->user_id;

        $match = Matched::firstOrCreate(
            [
                'buyer_id' => $currentUserId,
                'seller_id' => $sellerId,
                'product_id' => $product->id,
            ],
            [
                'is_active' => true,
            ]
        );

        $this->clearMatchCaches($currentUserId, $sellerId);

        return $match->wasRecentlyCreated;
    }

    private function clearMatchCaches(int $userId1, int $userId2): void
    {
        Cache::forget("user_matches_{$userId1}");
        Cache::forget("user_matches_{$userId2}");
        Cache::forget("user_conversations_{$userId1}");
        Cache::forget("user_conversations_{$userId2}");
    }

    private function moveToNextProduct(): void
    {
        if (count($this->productsStack) < self::MIN_STACK_SIZE) {
            $this->refillStack();
        }

        if (!empty($this->productsStack)) {
            $this->currentProduct = array_shift($this->productsStack);
        } else {
            $this->setEmptyState();
        }
    }

    private function refillStack(): void
    {
        try {
            $excludeIds = $this->getExcludedProductIds();

            $products = Product::query()
                ->active()
                ->where('user_id', '!=', auth()->id())
                ->whereNotIn('id', $excludeIds)
                ->whereNotIn('id', $this->getSwipedProductIds(auth()->id()))
                ->when($this->selectedCategory, fn($q) => $q->where('category_id', $this->selectedCategory))
                ->with(['category:id,name,icon', 'user:id,name'])
                ->select([
                    'id', 'title', 'description', 'price',
                    'condition', 'location', 'images',
                    'category_id', 'user_id', 'created_at'
                ])
                ->inRandomOrder()
                ->limit(self::STACK_SIZE)
                ->get();

            $newProducts = $products->map(fn($product) => $this->formatProduct($product))->toArray();

            $this->productsStack = array_merge($this->productsStack, $newProducts);
        } catch (\Exception $e) {
            $this->handleError(__('discover.errors.refill_failed'), $e);
        }
    }

    private function getExcludedProductIds(): array
    {
        return collect($this->productsStack)
            ->pluck('id')
            ->push($this->currentProduct['id'] ?? null)
            ->filter()
            ->toArray();
    }

    private function setEmptyState(): void
    {
        $this->noMoreProducts = true;
        $this->currentProduct = null;
        $this->productsStack = [];
    }

    private function resetState(): void
    {
        $this->productsStack = [];
        $this->currentProduct = null;
        $this->noMoreProducts = false;
    }

    private function notifyMatch(): void
    {
        $this->dispatch('show-match-notification');

        Notification::make()
            ->success()
            ->title(__('discover.notifications.match_title'))
            ->body(__('discover.notifications.match_body'))
            ->duration(5000)
            ->send();
    }

    private function handleError(string $message, \Exception $e): void
    {
        Log::error($message, [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => auth()->id(),
        ]);

        Notification::make()
            ->warning()
            ->title(__('discover.notifications.error_title'))
            ->body(__('discover.notifications.error_body'))
            ->send();
    }
}