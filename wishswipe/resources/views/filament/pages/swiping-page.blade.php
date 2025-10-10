<x-filament-panels::page>
    <style>
        .filter-filament-container {
            max-width: 500px;
            margin: -2rem auto 1rem;
            padding: 0 1rem;
            position: relative;
            z-index: 50;
        }

        .filter-filament-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .filter-filament-wrapper > div:first-child {
            flex: 1;
        }

        .swipe-container {
            perspective: 1500px;
            position: relative;
            width: 100%;
            max-width: 500px;
            height: 600px;
            margin: -3rem auto 0;
            z-index: 1;
        }

        .card-stack {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .swipe-card {
            position: absolute;
            width: 100%;
            height: 100%;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            cursor: grab;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s ease;
            will-change: transform;
            touch-action: none;
            user-select: none;
            opacity: 1;
        }

        .swipe-card * {
            pointer-events: none;
        }

        .swipe-card .card-badge,
        .swipe-card .card-title,
        .swipe-card .card-price {
            pointer-events: none;
        }

        .swipe-card.dragging {
            cursor: grabbing;
            transition: none;
        }

        .swipe-card.removing {
            transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55), opacity 0.4s ease;
        }

        .swipe-card:nth-child(2) {
            transform: scale(0.95) translateY(10px);
            opacity: 0.7;
            z-index: 1;
        }

        .swipe-card:nth-child(3) {
            transform: scale(0.9) translateY(20px);
            opacity: 0.5;
            z-index: 0;
        }

        .swipe-card:nth-child(n+4) {
            display: none;
        }

        .swipe-card:first-child {
            z-index: 10;
            opacity: 1;
        }

        .card-image {
            width: 100%;
            height: 70%;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-content {
            padding: 1.5rem;
            height: 30%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .card-price {
            font-size: 1.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .swipe-indicator {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 5rem;
            font-weight: 800;
            opacity: 0;
            transition: opacity 0.2s ease;
            pointer-events: none;
            z-index: 100;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .swipe-indicator.left {
            left: 2rem;
            color: #ef4444;
        }

        .swipe-indicator.right {
            right: 2rem;
            color: #10b981;
        }

        .swipe-indicator.active {
            opacity: 1;
            animation: pulse 0.3s ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.1); }
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1.5rem;
            position: relative;
            z-index: 20;
        }

        .action-btn {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .action-btn:active::before {
            width: 300px;
            height: 300px;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .action-btn:active {
            transform: scale(0.95);
        }

        .action-btn.nope {
            background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
            color: white;
        }

        .action-btn.like {
            background: linear-gradient(135deg, #51cf66, #37b24d);
            color: white;
        }

        .action-btn svg {
            width: 32px;
            height: 32px;
            position: relative;
            z-index: 1;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 600px;
            text-align: center;
            padding: 2rem;
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        .empty-state-icon svg {
            width: 60px;
            height: 60px;
            color: white;
        }

        .empty-state-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .empty-state-text {
            font-size: 1.125rem;
            color: #6b7280;
            margin-bottom: 2rem;
            max-width: 400px;
        }

        .loading-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 600px;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #e5e7eb;
            border-top-color: #667eea;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            margin-top: 1.5rem;
            font-size: 1.125rem;
            font-weight: 600;
            color: #6b7280;
        }

        .match-notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            text-align: center;
        }

        .match-notification.show {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .match-notification-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            color: white;
        }

        .match-notification-title {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
        }

        .match-notification-text {
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .keyboard-hint {
            text-align: center;
            margin-top: 1rem;
            color: #9ca3af;
            font-size: 0.875rem;
            position: relative;
            z-index: 20;
        }

        .keyboard-hint kbd {
            padding: 0.25rem 0.5rem;
            background: #f3f4f6;
            border-radius: 6px;
            font-family: monospace;
            margin: 0 0.25rem;
        }

        @media (max-width: 640px) {
            .filter-filament-container {
                padding: 0 0.5rem;
                margin: -1rem auto 0.5rem;
            }

            .swipe-container {
                max-width: 100%;
                height: 500px;
                margin: -1rem auto 0;
            }

            .swipe-card {
                touch-action: none;
            }

            .card-title {
                font-size: 1.25rem;
            }

            .card-price {
                font-size: 1.5rem;
            }

            .action-btn {
                width: 60px;
                height: 60px;
            }

            .action-btn svg {
                width: 28px;
                height: 28px;
            }

            .swipe-indicator {
                font-size: 4rem;
            }

            .action-buttons {
                margin-top: 1rem;
            }

            .filter-filament-wrapper {
                flex-direction: column;
                gap: 0.5rem;
            }

            .filter-filament-wrapper > div:first-child {
                width: 100%;
            }

            .keyboard-hint {
                display: none;
            }
        }

        @media (prefers-color-scheme: dark) {
            .swipe-card {
                background: #1f2937;
            }

            .card-title {
                color: #f9fafb;
            }

            .empty-state-title {
                color: #f9fafb;
            }

            .keyboard-hint kbd {
                background: #374151;
                color: #9ca3af;
            }
        }
    </style>


    <div class="filter-filament-container">
        <div class="filter-filament-wrapper">
            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="selectedCategory">
                    <option value="">All Categories</option>
                    @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
            
            @if($selectedCategory)
                <x-filament::button
                    wire:click="resetFilters"
                    color="gray"
                    size="md"
                    icon="heroicon-o-x-mark"
                    tooltip="Clear filter">
                    Clear
                </x-filament::button>
            @endif
        </div>
    </div>

    <div class="swipe-container" x-data="swipeHandler()">
        @if($noMoreProducts)
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="empty-state-title">You're All Caught Up!</h2>
                <p class="empty-state-text">
                    You've explored all available products. Check back soon for new items or adjust your filters.
                </p>
                <button 
                    wire:click="loadProducts" 
                    class="action-btn like"
                    style="position: relative; width: 70px; height: 70px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 32px; height: 32px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        @elseif($currentProduct)
            <div class="card-stack" id="cardStack">
                <div class="swipe-indicator left" id="nopeIndicator">✕</div>
                <div class="swipe-indicator right" id="likeIndicator">♥</div>

                <div class="swipe-card" id="currentCard">
                    @php
                        $hasImage = !empty($currentProduct['images']) && isset($currentProduct['images'][0]) && $currentProduct['images'][0];
                        $imageUrl = $hasImage ? Storage::url($currentProduct['images'][0]) : '';
                    @endphp
                    <div class="card-image" style="background-image: url('{{ $imageUrl }}'); background-size: cover; background-position: center;">
                        @if(!$hasImage)
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: rgba(255,255,255,0.5);">
                                <svg style="width: 80px; height: 80px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        
                        <div style="position: absolute; top: 1rem; right: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                            <span class="card-badge" style="background: rgba(255, 255, 255, 0.95); color: #1f2937; backdrop-filter: blur(10px);">
                                {{ $currentProduct['category']['name'] ?? 'Uncategorized' }}
                            </span>
                            <span class="card-badge" style="
                                @if($currentProduct['condition'] === 'new')
                                    background: rgba(16, 185, 129, 0.95); color: white;
                                @elseif($currentProduct['condition'] === 'like_new')
                                    background: rgba(59, 130, 246, 0.95); color: white;
                                @else
                                    background: rgba(251, 146, 60, 0.95); color: white;
                                @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $currentProduct['condition'])) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-content">
                        <div>
                            <h3 class="card-title">{{ $currentProduct['title'] }}</h3>
                            <div class="card-info">
                                <span class="card-price">${{ number_format($currentProduct['price'], 2) }}</span>
                                @if($currentProduct['location'])
                                    <span style="font-size: 0.875rem; color: #6b7280; display: flex; align-items: center; gap: 0.25rem;">
                                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $currentProduct['location'] }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(!empty($productsStack) && isset($productsStack[0]))
                    <div class="swipe-card">
                        <div class="card-image" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);"></div>
                        <div class="card-content"></div>
                    </div>
                @endif

                @if(!empty($productsStack) && isset($productsStack[1]))
                    <div class="swipe-card">
                        <div class="card-image" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);"></div>
                        <div class="card-content"></div>
                    </div>
                @endif
            </div>

            <div class="action-buttons">
                <button 
                    @click="swipeCard('left')" 
                    class="action-btn nope"
                    wire:loading.attr="disabled">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 32px; height: 32px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <button 
                    @click="swipeCard('right')" 
                    class="action-btn like"
                    wire:loading.attr="disabled">
                    <svg fill="currentColor" viewBox="0 0 20 20" style="width: 32px; height: 32px;">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div class="keyboard-hint">
                <kbd>←</kbd>
                Pass  •
                <kbd>→</kbd>
                Like
            </div>
        @else
            <div class="loading-state">
                <div class="loading-spinner"></div>
                <p class="loading-text">Loading amazing products...</p>
            </div>
        @endif
    </div>

    <div 
        class="match-notification" 
        id="matchNotification"
        x-data="{ show: false }"
        :class="{ 'show': show }"
        @show-match.window="show = true; setTimeout(() => show = false, 3000)">
        <svg class="match-notification-icon" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
        </svg>
        <h3 class="match-notification-title">It's a Match! 💫</h3>
        <p class="match-notification-text">You can now message each other</p>
    </div>

    @script
    <script>
        Alpine.data('swipeHandler', () => ({
            startX: 0,
            startY: 0,
            currentX: 0,
            currentY: 0,
            isDragging: false,
            card: null,

            init() {
                this.card = document.getElementById('currentCard');
                if (!this.card) return;

                this.bindEvents();
                this.setupKeyboard();
            },

            bindEvents() {
                this.card.addEventListener('mousedown', this.startDrag.bind(this));
                document.addEventListener('mousemove', this.drag.bind(this));
                document.addEventListener('mouseup', this.endDrag.bind(this));

                this.card.addEventListener('touchstart', this.startDrag.bind(this), { passive: false });
                document.addEventListener('touchmove', this.drag.bind(this), { passive: false });
                document.addEventListener('touchend', this.endDrag.bind(this));
            },

            startDrag(e) {
                if (e.type.includes('touch')) {
                    e.preventDefault();
                }
                
                this.isDragging = true;
                this.card.classList.add('dragging');

                const touch = e.type.includes('touch') ? e.touches[0] : e;
                this.startX = touch.clientX;
                this.startY = touch.clientY;
            },

            drag(e) {
                if (!this.isDragging) return;
                
                e.preventDefault();

                const touch = e.type.includes('touch') ? e.touches[0] : e;
                this.currentX = touch.clientX - this.startX;
                this.currentY = touch.clientY - this.startY;

                const constrainedY = this.currentY * 0.2;

                const rotation = this.currentX * 0.1;
                const opacity = 1 - Math.abs(this.currentX) / 500;

                this.card.style.transform = `translate(${this.currentX}px, ${constrainedY}px) rotate(${rotation}deg)`;
                this.card.style.opacity = Math.max(0.3, opacity);

                const nopeIndicator = document.getElementById('nopeIndicator');
                const likeIndicator = document.getElementById('likeIndicator');

                if (this.currentX < -50) {
                    nopeIndicator.classList.add('active');
                    likeIndicator.classList.remove('active');
                } else if (this.currentX > 50) {
                    likeIndicator.classList.add('active');
                    nopeIndicator.classList.remove('active');
                } else {
                    nopeIndicator.classList.remove('active');
                    likeIndicator.classList.remove('active');
                }
            },

            endDrag(e) {
                if (!this.isDragging) return;

                this.isDragging = false;
                this.card.classList.remove('dragging');

                const threshold = 150;

                if (Math.abs(this.currentX) > threshold) {
                    this.completeSwipe(this.currentX > 0 ? 'right' : 'left');
                } else {
                    this.resetCard();
                }

                document.getElementById('nopeIndicator').classList.remove('active');
                document.getElementById('likeIndicator').classList.remove('active');
            },

            swipeCard(direction) {
                if (!this.card) return;

                const distance = direction === 'right' ? 1000 : -1000;
                const rotation = direction === 'right' ? 30 : -30;
                const constrainedY = this.currentY * 0.2;

                this.card.classList.add('removing');
                this.card.style.transform = `translate(${distance}px, ${constrainedY}px) rotate(${rotation}deg)`;
                this.card.style.opacity = '0';

                setTimeout(() => {
                    if (direction === 'right') {
                        $wire.call('swipeRight');
                    } else {
                        $wire.call('swipeLeft');
                    }
                    this.resetCard();
                }, 400);
            },

            completeSwipe(direction) {
                this.swipeCard(direction);
            },

            resetCard() {
                if (!this.card) return;
                
                this.card.style.transform = '';
                this.card.style.opacity = '1';
                this.currentX = 0;
                this.currentY = 0;
            },

            setupKeyboard() {
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        this.swipeCard('left');
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        this.swipeCard('right');
                    }
                });
            }
        }));

        window.addEventListener('show-match-notification', () => {
            window.dispatchEvent(new CustomEvent('show-match'));
        });
    </script>
    @endscript
</x-filament-panels::page>