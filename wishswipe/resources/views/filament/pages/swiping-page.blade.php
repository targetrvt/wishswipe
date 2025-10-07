<x-filament-panels::page>
    <style>
        .swipe-container {
            perspective: 1500px;
            touch-action: pan-y pinch-zoom;
        }

        .swipe-card {
            position: relative;
            cursor: grab;
            transition: transform 0.3s ease, opacity 0.2s ease;
            transform-origin: center center;
            will-change: transform, opacity;
        }

        .swipe-card:active {
            cursor: grabbing;
        }

        .swipe-card.dragging {
            transition: none;
            z-index: 50;
        }

        .swipe-card.swiping-left {
            animation: swipeOutLeft 0.4s ease-out forwards;
        }

        .swipe-card.swiping-right {
            animation: swipeOutRight 0.4s ease-out forwards;
        }

        @keyframes swipeOutLeft {
            to {
                transform: translateX(-150%) rotate(-20deg);
                opacity: 0;
            }
        }

        @keyframes swipeOutRight {
            to {
                transform: translateX(150%) rotate(20deg);
                opacity: 0;
            }
        }

        .swipe-indicator {
            position: absolute;
            top: 20px;
            opacity: 0;
            transition: opacity 0.2s ease;
            pointer-events: none;
            z-index: 10;
        }

        .swipe-indicator.active {
            opacity: 1;
        }

        .swipe-indicator-left {
            left: 20px;
        }

        .swipe-indicator-right {
            right: 20px;
        }

        .action-btn {
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .action-btn:active {
            transform: scale(0.95);
        }

        .product-image {
            transition: transform 0.3s ease;
        }

        .swipe-card:hover .product-image {
            transform: scale(1.02);
        }

        .category-filter {
            transition: all 0.2s ease;
        }
    </style>

    <!-- Filter Section -->
    <div class="mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="flex-1 w-full sm:w-auto">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Filter by Category
                    </label>
                    <select wire:model.live="selectedCategory" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 category-filter">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::where('is_active', true)->get() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                @if($selectedCategory)
                    <div class="self-end">
                        <button wire:click="resetFilters" 
                                class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear Filter
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex justify-center items-center swipe-container" style="min-height: calc(100vh - 300px);">
        @if($noMoreProducts)
            <div class="text-center max-w-lg mx-auto px-4">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                    All Caught Up
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    You've explored all available products. Check back soon for new items.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button wire:click="loadProducts" 
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh Products
                    </button>
                    
                    @if($selectedCategory)
                        <button wire:click="resetFilters" 
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg font-medium transition-colors">
                            Browse All
                        </button>
                    @endif
                </div>
            </div>
        @elseif($currentProduct)
            <div class="w-full max-w-md mx-auto px-4" x-data="swipeCard()">
                <div class="relative">
                    <!-- Product Card -->
                    <div class="swipe-card bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700"
                         x-data="{ imageIndex: 0, maxImages: {{ count($currentProduct['images'] ?? []) }} }">
                        
                        <!-- Image Section -->
                        <div class="relative h-96 bg-gray-100 dark:bg-gray-900 overflow-hidden">
                            @if($currentProduct['images'] && count($currentProduct['images']) > 0)
                                @foreach($currentProduct['images'] as $index => $image)
                                    <div x-show="imageIndex === {{ $index }}"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         class="absolute inset-0">
                                        <img src="{{ Storage::url($image) }}" 
                                             alt="{{ $currentProduct['title'] }}"
                                             class="w-full h-full object-cover product-image">
                                    </div>
                                @endforeach
                                
                                @if(count($currentProduct['images']) > 1)
                                    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10">
                                        @foreach($currentProduct['images'] as $index => $image)
                                            <button 
                                                @click="imageIndex = {{ $index }}"
                                                class="h-2 rounded-full transition-all duration-300"
                                                :class="imageIndex === {{ $index }} ? 'bg-white w-8' : 'bg-white/50 w-2'">
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <svg class="w-20 h-20 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Swipe Indicators -->
                            <div class="swipe-indicator swipe-indicator-left">
                                <div class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg font-semibold">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Pass
                                </div>
                            </div>

                            <div class="swipe-indicator swipe-indicator-right">
                                <div class="flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-lg font-semibold">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                    </svg>
                                    Like
                                </div>
                            </div>

                            <!-- Category & Condition Tags -->
                            <div class="absolute top-4 right-4 flex flex-col gap-2">
                                <span class="inline-flex items-center px-3 py-1 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                    {{ $currentProduct['category']['name'] ?? 'Uncategorized' }}
                                </span>
                                
                                <span class="inline-flex items-center px-3 py-1 
                                    {{ $currentProduct['condition'] === 'new' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : ($currentProduct['condition'] === 'like_new' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400') }} 
                                    backdrop-blur-sm rounded-lg text-xs font-semibold border border-current/20">
                                    {{ ucfirst(str_replace('_', ' ', $currentProduct['condition'])) }}
                                </span>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex-1">
                                    {{ $currentProduct['title'] }}
                                </h2>
                                <div class="text-2xl font-bold text-primary-600 dark:text-primary-400 flex-shrink-0">
                                    ${{ number_format($currentProduct['price'], 2) }}
                                </div>
                            </div>

                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                {{ $currentProduct['description'] }}
                            </p>

                            <div class="flex flex-wrap gap-3 text-sm">
                                @if($currentProduct['location'])
                                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ $currentProduct['location'] }}</span>
                                    </div>
                                @endif

                                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ $currentProduct['user']['name'] ?? 'Unknown' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center items-center gap-6 mt-8">
                        <button 
                            onclick="animateSwipe('left', () => @this.call('swipeLeft'))"
                            wire:loading.attr="disabled"
                            class="action-btn w-16 h-16 bg-white dark:bg-gray-800 border-2 border-red-500 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full shadow-lg flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <button 
                            onclick="animateSwipe('right', () => @this.call('swipeRight'))"
                            wire:loading.attr="disabled"
                            class="action-btn w-20 h-20 bg-white dark:bg-gray-800 border-2 border-green-500 text-green-500 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-full shadow-lg flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <!-- Keyboard Shortcuts Hint -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Use arrow keys 
                            <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs font-mono mx-1">←</kbd>
                            <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs font-mono mx-1">→</kbd>
                            or drag the card
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center">
                <div class="w-16 h-16 border-4 border-primary-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Loading Products
                </p>
                <p class="text-gray-600 dark:text-gray-400">Finding great deals for you</p>
            </div>
        @endif
    </div>

    <!-- Match Notification -->
    <div 
        x-data="{ show: false }"
        @show-match-notification.window="show = true; setTimeout(() => show = false, 4000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full mx-4"
        style="display: none;">
        <div class="bg-green-500 rounded-xl shadow-2xl p-6 border border-green-600">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-bold text-white mb-1">
                        It's a Match!
                    </h4>
                    <p class="text-white/90 text-sm">
                        Check your messages to connect with the seller
                    </p>
                </div>
                <button @click="show = false" class="flex-shrink-0 text-white/70 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @script
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('swipeCard', () => ({
            startX: 0,
            startY: 0,
            currentX: 0,
            currentY: 0,
            isDragging: false,
            card: null,
            swipeThreshold: 100,
            
            init() {
                this.card = this.$el;
                this.bindEvents();
            },
            
            bindEvents() {
                this.card.addEventListener('mousedown', this.handleDragStart.bind(this));
                document.addEventListener('mousemove', this.handleDragMove.bind(this));
                document.addEventListener('mouseup', this.handleDragEnd.bind(this));
                this.card.addEventListener('touchstart', this.handleDragStart.bind(this));
                document.addEventListener('touchmove', this.handleDragMove.bind(this), { passive: false });
                document.addEventListener('touchend', this.handleDragEnd.bind(this));
            },
            
            handleDragStart(e) {
                if (e.target.closest('button') || e.target.closest('select')) return;
                
                this.isDragging = true;
                this.card.classList.add('dragging');
                
                const clientX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
                const clientY = e.type.includes('mouse') ? e.clientY : e.touches[0].clientY;
                
                this.startX = clientX;
                this.startY = clientY;
            },
            
            handleDragMove(e) {
                if (!this.isDragging) return;
                
                const clientX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
                const clientY = e.type.includes('mouse') ? e.clientY : e.touches[0].clientY;
                
                this.currentX = clientX - this.startX;
                this.currentY = clientY - this.startY;
                
                const rotation = this.currentX * 0.1;
                const opacity = Math.max(0.7, 1 - Math.abs(this.currentX) / 400);
                
                this.card.style.transform = `translateX(${this.currentX}px) translateY(${this.currentY}px) rotate(${rotation}deg)`;
                this.card.style.opacity = opacity;
                
                this.updateIndicators();
            },
            
            handleDragEnd(e) {
                if (!this.isDragging) return;
                
                this.isDragging = false;
                this.card.classList.remove('dragging');
                
                if (Math.abs(this.currentX) > this.swipeThreshold) {
                    if (this.currentX > 0) {
                        this.swipeRight();
                    } else {
                        this.swipeLeft();
                    }
                } else {
                    this.resetCard();
                }
                
                this.hideIndicators();
            },
            
            swipeLeft() {
                this.card.classList.add('swiping-left');
                setTimeout(() => {
                    this.$wire.call('swipeLeft');
                    this.resetCard();
                    this.card.classList.remove('swiping-left');
                }, 400);
            },
            
            swipeRight() {
                this.card.classList.add('swiping-right');
                setTimeout(() => {
                    this.$wire.call('swipeRight');
                    this.resetCard();
                    this.card.classList.remove('swiping-right');
                }, 400);
            },
            
            resetCard() {
                this.card.style.transform = '';
                this.card.style.opacity = '';
                this.currentX = 0;
                this.currentY = 0;
            },
            
            updateIndicators() {
                const leftIndicator = this.card.querySelector('.swipe-indicator-left');
                const rightIndicator = this.card.querySelector('.swipe-indicator-right');
                
                if (this.currentX < -50) {
                    leftIndicator?.classList.add('active');
                    rightIndicator?.classList.remove('active');
                } else if (this.currentX > 50) {
                    rightIndicator?.classList.add('active');
                    leftIndicator?.classList.remove('active');
                } else {
                    leftIndicator?.classList.remove('active');
                    rightIndicator?.classList.remove('active');
                }
            },
            
            hideIndicators() {
                const leftIndicator = this.card.querySelector('.swipe-indicator-left');
                const rightIndicator = this.card.querySelector('.swipe-indicator-right');
                leftIndicator?.classList.remove('active');
                rightIndicator?.classList.remove('active');
            }
        }));
    });

    function animateSwipe(direction, wireMethod) {
        const card = document.querySelector('.swipe-card');
        if (!card) return;
        
        card.classList.add(`swiping-${direction}`);
        setTimeout(() => {
            wireMethod();
            card.classList.remove(`swiping-${direction}`);
        }, 400);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            animateSwipe('left', () => @this.call('swipeLeft'));
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            animateSwipe('right', () => @this.call('swipeRight'));
        }
    });
    </script>
    @endscript
</x-filament-panels::page>