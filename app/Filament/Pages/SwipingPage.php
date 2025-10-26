<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use App\Models\Swipe;
use App\Models\Matched;
use App\Models\Category;
use App\Models\Message;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Actions as FormActions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Hidden;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;

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
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public ?array $locationCoordinates = null; // For map field
    public ?string $locationText = null; // Display field for location name
    public ?float $locationLatitude = null;
    public ?float $locationLongitude = null;
    public ?int $radiusKm = 50; // Default radius in kilometers
    
    // Negotiate form properties
    public bool $showNegotiateForm = false;
    public ?float $negotiatePrice = null;
    public ?string $negotiateMessage = null;

    private const STACK_SIZE = 15;
    private const MIN_STACK_SIZE = 5;
    private const CACHE_TTL = 3600;

    public function mount(): void
    {
        $this->loadProducts();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('filters')
                ->label(__('discover.filters.filter_button'))
                ->icon('heroicon-o-funnel')
                ->badge($this->hasActiveFilters() ? __('discover.filters.active') : null)
                ->badgeColor('success')
                ->form([                  
                    Grid::make(2)
                        ->schema([
                            TextInput::make('minPrice')
                                ->label(__('discover.filters.min_price'))
                                ->numeric()
                                ->minValue(0)
                                ->step(0.01)
                                ->prefix('€')
                                ->placeholder('0.00'),
                            
                            TextInput::make('maxPrice')
                                ->label(__('discover.filters.max_price'))
                                ->numeric()
                                ->minValue(0)
                                ->step(0.01)
                                ->prefix('€')
                                ->placeholder('0.00'),
                        ]),
                    
                    Section::make(__('discover.filters.location_filter'))
                        ->description(__('discover.filters.location_description'))
                        ->icon('heroicon-o-map-pin')
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('locationText')
                                ->label(__('discover.filters.search_location'))
                                ->placeholder(__('discover.filters.location_placeholder'))
                                ->helperText(__('discover.filters.location_helper'))
                                ->reactive()
                                ->debounce(1000),
                            
                            Map::make('locationCoordinates')
                                ->label(__('discover.filters.map_label'))
                                ->mapControls([
                                    'mapTypeControl' => true,
                                    'scaleControl' => true,
                                    'streetViewControl' => false,
                                    'rotateControl' => false,
                                    'fullscreenControl' => true,
                                    'searchBoxControl' => false,
                                    'zoomControl' => true,
                                ])
                                ->height(fn () => '350px')
                                ->defaultZoom(5)
                                ->autocomplete('locationText')
                                ->autocompleteReverse(true)
                                ->reverseGeocode([
                                    'locationText' => '%A1, %c',
                                ])
                                ->defaultLocation([56.9496, 24.1052]) // Riga, Latvia
                                ->draggable()
                                ->clickable()
                                ->geolocate()
                                ->geolocateLabel(__('discover.filters.geolocate_label'))
                                ->geolocateOnLoad(false, false)
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if (is_array($state) && isset($state['lat']) && isset($state['lng'])) {
                                        $lat = round((float) $state['lat'], 7);
                                        $lng = round((float) $state['lng'], 7);
                                        $set('locationLatitude', $lat);
                                        $set('locationLongitude', $lng);
                                        
                                        // Get the location text and clean it
                                        $locationText = $get('locationText');
                                        if ($locationText) {
                                            // Remove Plus Code pattern (e.g., "X494+36 ")
                                            $cleanText = preg_replace('/^[A-Z0-9]{4}\+[A-Z0-9]{2}\s+/', '', $locationText);
                                            // Split by comma
                                            $parts = array_map('trim', explode(',', $cleanText));
                                            // Filter out postal codes (like LV-1010) and empty parts
                                            $parts = array_filter($parts, function($part) {
                                                return !empty($part) && !preg_match('/^[A-Z]{2}-?\d+$/', $part);
                                            });
                                            // Remove duplicates
                                            $parts = array_unique($parts);
                                            // Get last 2 parts (region/city and country)
                                            if (count($parts) >= 2) {
                                                $country = array_pop($parts);
                                                $region = array_pop($parts);
                                                $cleanText = "$region, $country";
                                            } elseif (count($parts) === 1) {
                                                $cleanText = $parts[0];
                                            }
                                            $set('locationText', $cleanText);
                                        }
                                    }
                                })
                                ->helperText(__('discover.filters.map_helper')),
                            
                            Hidden::make('locationLatitude'),
                            Hidden::make('locationLongitude'),
                            
                            TextInput::make('radiusKm')
                                ->label(__('discover.filters.radius') . ' (km)')
                                ->numeric()
                                ->minValue(5)
                                ->maxValue(200)
                                ->step(5)
                                ->default(50)
                                ->suffix('km')
                                ->helperText(__('discover.filters.radius_helper'))
                                ->visible(fn (callable $get) => $get('locationLatitude') !== null && $get('locationLongitude') !== null),
                        ]),
                ])
                ->modalWidth('lg')
                ->action(function (array $data): void {
                    try {
                        $this->minPrice = !empty($data['minPrice']) ? (float) $data['minPrice'] : null;
                        $this->maxPrice = !empty($data['maxPrice']) ? (float) $data['maxPrice'] : null;
                        $this->locationCoordinates = $data['locationCoordinates'] ?? null;
                        $this->locationText = $data['locationText'] ?? null;
                        $this->locationLatitude = !empty($data['locationLatitude']) ? (float) $data['locationLatitude'] : null;
                        $this->locationLongitude = !empty($data['locationLongitude']) ? (float) $data['locationLongitude'] : null;
                        $this->radiusKm = !empty($data['radiusKm']) ? (int) $data['radiusKm'] : 50;
                        
                        $this->resetState();
                        $this->loadProducts();
                        
                        Notification::make()
                            ->success()
                            ->title(__('discover.filters.applied'))
                            ->send();
                    } catch (\Exception $e) {
                        \Log::error('Filter error: ' . $e->getMessage(), [
                            'data' => $data,
                            'trace' => $e->getTraceAsString()
                        ]);
                        
                        Notification::make()
                            ->danger()
                            ->title('Error applying filters')
                            ->body($e->getMessage())
                            ->send();
                    }
                })
                ->fillForm([
                    'minPrice' => $this->minPrice,
                    'maxPrice' => $this->maxPrice,
                    'locationCoordinates' => $this->locationCoordinates,
                    'locationText' => $this->locationText,
                    'locationLatitude' => $this->locationLatitude,
                    'locationLongitude' => $this->locationLongitude,
                    'radiusKm' => $this->radiusKm,
                ])
                ->modalSubmitActionLabel(__('discover.filters.apply'))
                ->modalCancelActionLabel(__('discover.filters.cancel'))
                ->extraModalFooterActions([
                    Action::make('reset')
                        ->label(__('discover.filters.reset'))
                        ->icon('heroicon-o-x-mark')
                        ->color('gray')
                        ->action(function () {
                            $this->resetFilters();
                        }),
                ]),
        ];
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

    public function negotiate(): void
    {
        if (!$this->currentProduct) {
            return;
        }

        // Show negotiate form instead of directly creating message
        $this->showNegotiateForm = true;
        $this->negotiatePrice = null; // No default value, user must enter it
        $this->negotiateMessage = null;
    }

    public function submitNegotiate(): void
    {
        if (!$this->currentProduct || !$this->negotiatePrice) {
            Notification::make()
                ->title(__('discover.negotiate.error_title'))
                ->body(__('discover.negotiate.error_price_required'))
                ->danger()
                ->send();
            return;
        }

        if ($this->negotiatePrice >= $this->currentProduct['price']) {
            Notification::make()
                ->title(__('discover.negotiate.error_title'))
                ->body(__('discover.negotiate.error_price_too_high'))
                ->danger()
                ->send();
            return;
        }

        try {
            DB::beginTransaction();

            $this->recordSwipe('right'); // Also record as a right swipe

            $product = Product::find($this->currentProduct['id']);

            if ($product && $this->createMatchIfMutual($product)) {
                $this->notifyMatch();
                
                // Create a negotiate request message in the conversation
                $this->createNegotiateRequestMessage($product, $this->negotiatePrice, $this->negotiateMessage);
            }

            DB::commit();
            $this->moveToNextProduct();
            $this->showNegotiateForm = false;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleError(__('discover.errors.swipe_failed'), $e);
            $this->moveToNextProduct();
        }
    }

    public function cancelNegotiate(): void
    {
        $this->showNegotiateForm = false;
        $this->negotiatePrice = null;
        $this->negotiateMessage = null;
    }

    private function createNegotiateRequestMessage($product, $proposedPrice, $message = null): void
    {
        $match = Matched::where('buyer_id', Auth::id())
            ->where('seller_id', $product->user_id)
            ->where('product_id', $product->id)
            ->first();

        if ($match && $match->conversation) {
            Message::create([
                'conversation_id' => $match->conversation->id,
                'user_id' => Auth::id(),
                'content' => json_encode([
                    'type' => 'negotiate_request',
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'original_price' => $product->price,
                    'proposed_price' => $proposedPrice,
                    'message' => $message ?: 'I would like to negotiate the price for this item.'
                ]),
                'is_read' => false,
            ]);
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
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->locationCoordinates = null;
        $this->locationText = null;
        $this->locationLatitude = null;
        $this->locationLongitude = null;
        $this->radiusKm = 50;
        $this->resetState();
        $this->loadProducts();
        
        Notification::make()
            ->success()
            ->title(__('discover.filters.reset'))
            ->body(__('discover.filters.reset_success'))
            ->send();
    }

    public function hasActiveFilters(): bool
    {
        return $this->selectedCategory !== null 
            || $this->minPrice !== null 
            || $this->maxPrice !== null 
            || $this->locationLatitude !== null
            || $this->locationLongitude !== null;
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

        $query = Product::query()
            ->active()
            ->where('user_id', '!=', $userId)
            ->whereNotIn('id', $this->getSwipedProductIds($userId))
            ->when($this->selectedCategory, fn($q) => $q->where('category_id', $this->selectedCategory))
            ->when($this->minPrice !== null, fn($q) => $q->where('price', '>=', $this->minPrice))
            ->when($this->maxPrice !== null, fn($q) => $q->where('price', '<=', $this->maxPrice));

        // Apply location radius filtering (coordinates-based)
        if ($this->locationLatitude !== null && $this->locationLongitude !== null && $this->radiusKm) {
            $query->withinRadius($this->locationLatitude, $this->locationLongitude, $this->radiusKm);
            
            return $query
                ->with(['category:id,name,icon', 'user:id,name'])
                ->select([
                    'id', 'title', 'description', 'price',
                    'condition', 'location', 'images',
                    'category_id', 'user_id', 'created_at',
                    'latitude', 'longitude', 'is_negotiable'
                ])
                ->limit($limit)
                ->get();
        }

        return $query
            ->with(['category:id,name,icon', 'user:id,name'])
            ->select([
                'id', 'title', 'description', 'price',
                'condition', 'location', 'images',
                'category_id', 'user_id', 'created_at',
                'latitude', 'longitude', 'is_negotiable'
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
            'is_negotiable' => $product->is_negotiable ?? false,
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

            $query = Product::query()
                ->active()
                ->where('user_id', '!=', auth()->id())
                ->whereNotIn('id', $excludeIds)
                ->whereNotIn('id', $this->getSwipedProductIds(auth()->id()))
                ->when($this->selectedCategory, fn($q) => $q->where('category_id', $this->selectedCategory))
                ->when($this->minPrice !== null, fn($q) => $q->where('price', '>=', $this->minPrice))
                ->when($this->maxPrice !== null, fn($q) => $q->where('price', '<=', $this->maxPrice));

            // Apply location radius filtering (coordinates-based)
            if ($this->locationLatitude !== null && $this->locationLongitude !== null && $this->radiusKm) {
                $query->withinRadius($this->locationLatitude, $this->locationLongitude, $this->radiusKm);
                
                $products = $query
                    ->with(['category:id,name,icon', 'user:id,name'])
                    ->limit(self::STACK_SIZE)
                    ->get();
            } else {
                $products = $query
                    ->with(['category:id,name,icon', 'user:id,name'])
                    ->select([
                        'id', 'title', 'description', 'price',
                        'condition', 'location', 'images',
                        'category_id', 'user_id', 'created_at',
                        'latitude', 'longitude'
                    ])
                    ->inRandomOrder()
                    ->limit(self::STACK_SIZE)
                    ->get();
            }

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