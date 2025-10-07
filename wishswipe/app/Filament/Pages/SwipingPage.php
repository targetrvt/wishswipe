<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use App\Models\Swipe;
use App\Models\Category;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SwipingPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-hand-raised';
    
    protected static string $view = 'filament.pages.swiping-page';
    
    protected static ?string $title = 'Swipe Products';
    
    protected static ?string $navigationLabel = 'Discover';
    
    protected static ?int $navigationSort = 1;

    public $currentProduct = null;
    public $productsStack = [];
    public $noMoreProducts = false;
    public $selectedCategory = null;
    
    // Configuration
    protected int $stackSize = 15;
    protected int $minStackSize = 5;

    public function mount(): void
    {
        $this->loadProducts();
    }

    /**
     * Load products into the stack
     */
    public function loadProducts(): void
    {
        $userId = auth()->id();
        
        // Build query for unviewed products
        $query = Product::query()
            ->active()
            ->where('user_id', '!=', $userId)
            ->whereNotIn('id', function($query) use ($userId) {
                $query->select('product_id')
                    ->from('swipes')
                    ->where('user_id', $userId);
            })
            ->with(['category', 'user:id,name'])
            ->select([
                'id',
                'title',
                'description',
                'price',
                'condition',
                'location',
                'images',
                'category_id',
                'user_id',
                'created_at'
            ]);

        // Apply category filter if selected
        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        // Get products in random order
        $products = $query
            ->inRandomOrder()
            ->limit($this->stackSize)
            ->get();

        // Handle empty results
        if ($products->isEmpty()) {
            $this->noMoreProducts = true;
            $this->currentProduct = null;
            $this->productsStack = [];
            return;
        }

        // Convert to array and prepare stack
        $productsArray = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'description' => $product->description,
                'price' => $product->price,
                'condition' => $product->condition,
                'location' => $product->location,
                'images' => is_array($product->images) ? $product->images : json_decode($product->images, true),
                'category' => [
                    'id' => $product->category?->id,
                    'name' => $product->category?->name ?? 'Uncategorized',
                ],
                'user' => [
                    'id' => $product->user?->id,
                    'name' => $product->user?->name ?? 'Unknown',
                ],
                'created_at' => $product->created_at?->diffForHumans(),
            ];
        })->toArray();

        // Set current product and stack
        if (empty($this->currentProduct) && !empty($productsArray)) {
            $this->currentProduct = array_shift($productsArray);
        }
        
        $this->productsStack = $productsArray;
        $this->noMoreProducts = false;
    }

    /**
     * Handle left swipe (pass/reject)
     */
    public function swipeLeft(): void
    {
        if (!$this->currentProduct) {
            return;
        }

        try {
            // Record the swipe
            Swipe::create([
                'user_id' => auth()->id(),
                'product_id' => $this->currentProduct['id'],
                'direction' => 'left',
            ]);

            $this->nextProduct();
        } catch (\Exception $e) {
            // Log error but continue
            logger()->error('Swipe left error: ' . $e->getMessage());
            $this->nextProduct();
        }
    }

    /**
     * Handle right swipe (like/accept)
     */
    public function swipeRight(): void
    {
        if (!$this->currentProduct) {
            return;
        }

        try {
            DB::beginTransaction();

            // Record the swipe
            $swipe = Swipe::create([
                'user_id' => auth()->id(),
                'product_id' => $this->currentProduct['id'],
                'direction' => 'right',
            ]);

            // Check for match and create if exists
            $product = Product::find($this->currentProduct['id']);
            
            if ($product) {
                $matchCreated = $this->checkAndCreateMatch($product);
                
                if ($matchCreated) {
                    $this->dispatch('show-match-notification');
                }
            }

            DB::commit();
            $this->nextProduct();
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Swipe right error: ' . $e->getMessage());
            $this->nextProduct();
        }
    }

    /**
     * Check if both users liked each other's products and create match
     */
    protected function checkAndCreateMatch(Product $product): bool
    {
        $currentUserId = auth()->id();
        $productOwnerId = $product->user_id;

        // Check if the product owner also swiped right on any of current user's products
        $mutualLike = Swipe::where('user_id', $productOwnerId)
            ->where('direction', 'right')
            ->whereHas('product', function($query) use ($currentUserId) {
                $query->where('user_id', $currentUserId);
            })
            ->exists();

        if ($mutualLike) {
            // Create match (implement your match creation logic)
            // This could be a separate Match model or notification
            
            // Example: Create conversation or send notification
            // Match::firstOrCreate([
            //     'user_id_1' => min($currentUserId, $productOwnerId),
            //     'user_id_2' => max($currentUserId, $productOwnerId),
            // ]);
            
            return true;
        }

        return false;
    }

    /**
     * Move to the next product
     */
    protected function nextProduct(): void
    {
        // Check if we need to load more products
        if (count($this->productsStack) < $this->minStackSize) {
            $this->loadMoreProducts();
        }

        // Get next product from stack
        if (!empty($this->productsStack)) {
            $this->currentProduct = array_shift($this->productsStack);
        } else {
            $this->noMoreProducts = true;
            $this->currentProduct = null;
        }
    }

    /**
     * Load additional products to refill the stack
     */
    protected function loadMoreProducts(): void
    {
        $userId = auth()->id();
        
        // Get IDs of products already in stack or current
        $excludeIds = collect($this->productsStack)
            ->pluck('id')
            ->push($this->currentProduct['id'] ?? null)
            ->filter()
            ->toArray();

        // Load more products
        $products = Product::query()
            ->active()
            ->where('user_id', '!=', $userId)
            ->whereNotIn('id', $excludeIds)
            ->whereNotIn('id', function($query) use ($userId) {
                $query->select('product_id')
                    ->from('swipes')
                    ->where('user_id', $userId);
            })
            ->when($this->selectedCategory, function($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->with(['category', 'user:id,name'])
            ->select([
                'id',
                'title',
                'description',
                'price',
                'condition',
                'location',
                'images',
                'category_id',
                'user_id',
                'created_at'
            ])
            ->inRandomOrder()
            ->limit($this->stackSize)
            ->get();

        // Add to stack
        $newProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'description' => $product->description,
                'price' => $product->price,
                'condition' => $product->condition,
                'location' => $product->location,
                'images' => is_array($product->images) ? $product->images : json_decode($product->images, true),
                'category' => [
                    'id' => $product->category?->id,
                    'name' => $product->category?->name ?? 'Uncategorized',
                ],
                'user' => [
                    'id' => $product->user?->id,
                    'name' => $product->user?->name ?? 'Unknown',
                ],
                'created_at' => $product->created_at?->diffForHumans(),
            ];
        })->toArray();

        $this->productsStack = array_merge($this->productsStack, $newProducts);
    }

    /**
     * Handle category filter change
     */
    public function updatedSelectedCategory(): void
    {
        // Clear current state
        $this->productsStack = [];
        $this->currentProduct = null;
        $this->noMoreProducts = false;
        
        // Load products with new filter
        $this->loadProducts();
    }

    /**
     * Reset all filters
     */
    public function resetFilters(): void
    {
        $this->selectedCategory = null;
        $this->productsStack = [];
        $this->currentProduct = null;
        $this->noMoreProducts = false;
        
        $this->loadProducts();
    }

    /**
     * Get the categories for the filter dropdown
     */
    public function getCategoriesProperty()
    {
        return Cache::remember('active_categories', 3600, function () {
            return Category::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']);
        });
    }
}