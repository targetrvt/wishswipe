<x-filament-panels::page>
    <style>
        .swipe-container {
            perspective: 1500px;
            touch-action: pan-y pinch-zoom;
        }

        .swipe-card {
            position: relative;
            cursor: grab;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1),
                        opacity 0.3s ease,
                        box-shadow 0.3s ease;
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
            animation: swipeOutLeft 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        .swipe-card.swiping-right {
            animation: swipeOutRight 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        @keyframes swipeOutLeft {
            0% {
                transform: translateX(0) rotate(0deg) scale(1);
                opacity: 1;
            }
            100% {
                transform: translateX(-200%) rotate(-35deg) scale(0.8);
                opacity: 0;
            }
        }

        @keyframes swipeOutRight {
            0% {
                transform: translateX(0) rotate(0deg) scale(1);
                opacity: 1;
            }
            100% {
                transform: translateX(200%) rotate(35deg) scale(0.8);
                opacity: 0;
            }
        }

        .swipe-indicator {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            pointer-events: none;
            z-index: 10;
        }

        .swipe-indicator.active {
            opacity: 1;
            transform: translateY(-50%) scale(1.1);
        }

        .swipe-indicator-left {
            left: 30px;
        }

        .swipe-indicator-right {
            right: 30px;
        }

        .swipe-badge {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 8px solid;
            backdrop-filter: blur(16px);
            position: relative;
        }

        .swipe-badge::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            padding: 4px;
            background: linear-gradient(45deg, transparent 40%, currentColor 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.5;
            animation: rotate 2s linear infinite;
        }

        @keyframes rotate {
            to { transform: rotate(360deg); }
        }

        .swipe-badge.nope {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.95), rgba(220, 38, 38, 0.95));
            border-color: #dc2626;
            color: #dc2626;
            box-shadow: 0 20px 60px rgba(239, 68, 68, 0.6),
                        0 0 80px rgba(239, 68, 68, 0.3);
        }

        .swipe-badge.like {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(5, 150, 105, 0.95));
            border-color: #059669;
            color: #059669;
            box-shadow: 0 20px 60px rgba(16, 185, 129, 0.6),
                        0 0 80px rgba(16, 185, 129, 0.3);
        }

        .action-button {
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .action-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .action-button:active::before {
            width: 400px;
            height: 400px;
        }

        .action-button::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: inherit;
            padding: 2px;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        .card-shadow {
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.05),
                0 20px 50px -10px rgba(0, 0, 0, 0.1),
                0 30px 70px -15px rgba(0, 0, 0, 0.12),
                0 0 0 1px rgba(0, 0, 0, 0.04);
        }

        .dark .card-shadow {
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.3),
                0 20px 50px -10px rgba(0, 0, 0, 0.4),
                0 30px 70px -15px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        .image-dot {
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        @keyframes pulse-match {
            0%, 100% {
                transform: scale(1);
            }
            25% {
                transform: scale(1.08);
            }
            50% {
                transform: scale(1.04);
            }
            75% {
                transform: scale(1.06);
            }
        }

        .pulse-match {
            animation: pulse-match 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        .gradient-border {
            position: relative;
            background: linear-gradient(to right, #f3f4f6, #f9fafb);
            background-clip: padding-box;
            border: 2px solid transparent;
        }

        .dark .gradient-border {
            background: linear-gradient(to right, #1f2937, #111827);
        }

        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 2px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass-effect {
            background: rgba(31, 41, 55, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @keyframes spin-slow {
            to { transform: rotate(360deg); }
        }

        .spin-slow {
            animation: spin-slow 3s linear infinite;
        }

        .category-badge {
            position: relative;
            overflow: hidden;
        }

        .category-badge::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transform: translateX(-100%);
            animation: slide 3s infinite;
        }

        @keyframes slide {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>

    <div class="mb-8">
        <div class="glass-effect rounded-3xl shadow-xl p-6 gradient-border">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <div class="flex-1 w-full sm:w-auto">
                    <label class="block text-sm font-black text-gray-900 dark:text-white mb-3 tracking-wide uppercase">
                        <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Filter by Category
                        </span>
                    </label>
                    <select wire:model.live="selectedCategory" 
                            class="w-full rounded-2xl border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-lg focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 font-bold text-lg transition-all">
                        <option value="">🌟 All Categories</option>
                        @foreach(\App\Models\Category::where('is_active', true)->get() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                @if($selectedCategory)
                    <div class="self-end">
                        <button wire:click="resetFilters" 
                                class="flex items-center gap-3 px-6 py-3.5 text-sm font-black text-white bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 rounded-2xl transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear Filter
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="flex justify-center items-center swipe-container" style="min-height: calc(100vh - 300px);">
        @if($noMoreProducts)
            <div class="text-center max-w-lg mx-auto px-4">
                <div class="relative inline-block mb-8 floating">
                    <div class="w-40 h-40 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-full flex items-center justify-center shadow-2xl">
                        <svg class="w-20 h-20 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="absolute -top-2 -right-2 w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center shadow-xl animate-bounce">
                        <span class="text-3xl">🎉</span>
                    </div>
                </div>
                
                <h2 class="text-4xl font-black text-gray-900 dark:text-white mb-4 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    All Caught Up!
                </h2>
                <p class="text-gray-600 dark:text-gray-300 mb-8 text-xl font-semibold">
                    You've explored all available products. Check back soon for fresh arrivals!
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button wire:click="loadProducts" 
                            class="flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 text-white rounded-2xl font-black text-lg transition-all transform hover:scale-105 shadow-2xl hover:shadow-indigo-500/50">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh Products
                    </button>
                    
                    @if($selectedCategory)
                        <button wire:click="resetFilters" 
                                class="flex items-center justify-center gap-3 px-8 py-4 glass-effect text-gray-900 dark:text-white border-2 border-gray-300 dark:border-gray-600 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl font-black text-lg transition-all transform hover:scale-105 shadow-xl">
                            Browse All
                        </button>
                    @endif
                </div>
            </div>
        @elseif($currentProduct)
            <div class="w-full max-w-md mx-auto px-4" x-data="swipeCard()">
                <div class="relative">
                    <div class="swipe-card glass-effect rounded-[2rem] card-shadow overflow-hidden border-2 border-white/50 dark:border-gray-700/50"
                         x-data="{ imageIndex: 0, maxImages: {{ count($currentProduct['images'] ?? []) }} }">
                        
                        <div class="relative h-[550px] bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 dark:from-gray-800 dark:via-gray-900 dark:to-black overflow-hidden">
                            @if($currentProduct['images'] && count($currentProduct['images']) > 0)
                                @foreach($currentProduct['images'] as $index => $image)
                                    <div x-show="imageIndex === {{ $index }}"
                                         x-transition:enter="transition ease-out duration-500"
                                         x-transition:enter-start="opacity-0 scale-105"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute inset-0">
                                        <img src="{{ Storage::url($image) }}" 
                                             alt="{{ $currentProduct['title'] }}"
                                             class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                    </div>
                                @endforeach
                                
                                @if(count($currentProduct['images']) > 1)
                                    <div class="absolute top-6 left-0 right-0 flex justify-center gap-2 px-4 z-10">
                                        @foreach($currentProduct['images'] as $index => $image)
                                            <button 
                                                @click="imageIndex = {{ $index }}"
                                                class="image-dot h-1.5 rounded-full transition-all duration-500"
                                                :class="imageIndex === {{ $index }} ? 'bg-white w-12 shadow-lg' : 'bg-white/50 w-1.5 hover:bg-white/70'">
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <div class="text-center">
                                        <div class="w-32 h-32 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 backdrop-blur-sm">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 font-bold text-lg">No image available</p>
                                    </div>
                                </div>
                            @endif

                            <div class="swipe-indicator swipe-indicator-left">
                                <div class="swipe-badge nope">
                                    <svg class="w-20 h-20 text-white drop-shadow-2xl" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            </div>

                            <div class="swipe-indicator swipe-indicator-right">
                                <div class="swipe-badge like">
                                    <svg class="w-20 h-20 text-white drop-shadow-2xl" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>

                            <div class="absolute top-6 right-6 z-10 flex flex-col gap-3">
                                <span class="category-badge inline-flex items-center gap-2 px-4 py-2 glass-effect rounded-2xl text-sm font-black text-gray-900 dark:text-white shadow-2xl border border-white/30 dark:border-gray-700/30">
                                    <span class="w-2 h-2 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 animate-pulse"></span>
                                    {{ $currentProduct['category']['name'] ?? 'Uncategorized' }}
                                </span>
                                
                                <span class="inline-flex items-center gap-2 px-4 py-2 
                                    {{ $currentProduct['condition'] === 'new' ? 'bg-gradient-to-r from-emerald-500 to-teal-600' : ($currentProduct['condition'] === 'like_new' ? 'bg-gradient-to-r from-blue-500 to-cyan-600' : 'bg-gradient-to-r from-amber-500 to-orange-600') }} 
                                    text-white rounded-2xl text-sm font-black shadow-2xl border-2 border-white/30">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    {{ ucfirst(str_replace('_', ' ', $currentProduct['condition'])) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-8 bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900">
                            <div class="flex items-start justify-between gap-6 mb-6">
                                <h2 class="text-3xl font-black text-gray-900 dark:text-white flex-1 leading-tight">
                                    {{ $currentProduct['title'] }}
                                </h2>
                                <div class="flex-shrink-0">
                                    <div class="text-4xl font-black bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                                        ${{ number_format($currentProduct['price'], 2) }}
                                    </div>
                                </div>
                            </div>

                            <p class="text-gray-700 dark:text-gray-300 mb-6 line-clamp-3 text-base leading-relaxed font-medium">
                                {{ $currentProduct['description'] }}
                            </p>

                            <div class="flex flex-wrap gap-4 text-sm">
                                @if($currentProduct['location'])
                                    <div class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-xl border border-indigo-100 dark:border-indigo-800">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="font-black text-gray-900 dark:text-white">{{ $currentProduct['location'] }}</span>
                                    </div>
                                @endif

                                <div class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30 rounded-xl border border-purple-100 dark:border-purple-800">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="font-black text-gray-900 dark:text-white">{{ $currentProduct['user']['name'] ?? 'Unknown' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center items-center gap-8 mt-10">
                        <button 
                            onclick="animateSwipe('left', () => @this.call('swipeLeft'))"
                            wire:loading.attr="disabled"
                            class="action-button group w-24 h-24 bg-gradient-to-br from-red-500 via-rose-600 to-pink-600 hover:from-red-600 hover:via-rose-700 hover:to-pink-700 rounded-full shadow-2xl flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-12 h-12 text-white drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <button 
                            onclick="animateSwipe('right', () => @this.call('swipeRight'))"
                            wire:loading.attr="disabled"
                            class="action-button group w-28 h-28 bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-600 hover:from-emerald-600 hover:via-teal-700 hover:to-cyan-700 rounded-full shadow-2xl flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-14 h-14 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex justify-center items-center gap-8 mt-5">
                        <span class="text-sm font-black text-gray-500 dark:text-gray-400 w-24 text-center uppercase tracking-wider">Nope</span>
                        <span class="text-sm font-black text-gray-500 dark:text-gray-400 w-28 text-center uppercase tracking-wider">Like</span>
                    </div>

                    <div class="text-center mt-8">
                        <div class="inline-flex items-center gap-3 px-6 py-3 glass-effect rounded-2xl border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-bold">
                                Drag card or use <kbd class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded text-xs font-mono">← →</kbd> keys
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center">
                <div class="relative inline-block mb-8">
                    <div class="w-28 h-28 border-4 border-transparent border-t-indigo-600 border-r-purple-600 border-b-pink-600 rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center spin-slow">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="text-2xl font-black bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-3">
                    Loading Products...
                </p>
                <p class="text-gray-600 dark:text-gray-400 font-semibold text-lg">Finding amazing deals for you ✨</p>
            </div>
        @endif
    </div>

    <div 
        x-data="{ show: false }"
        @show-match-notification.window="show = true; setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-8 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-90"
        class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full mx-4 pulse-match"
        style="display: none;">
        <div class="bg-gradient-to-r from-emerald-500 via-teal-600 to-cyan-600 rounded-3xl shadow-2xl p-8 border-4 border-white/30 backdrop-blur-lg">
            <div class="flex items-center gap-5">
                <div class="flex-shrink-0 w-20 h-20 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center border-4 border-white/50 animate-bounce">
                    <svg class="w-10 h-10 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-2xl font-black text-white mb-2 flex items-center gap-2">
                        It's a Match! 
                        <span class="text-3xl animate-bounce">🎉</span>
                    </h4>
                    <p class="text-white/95 text-base font-bold">
                        Check your messages to connect with the seller
                    </p>
                </div>
                <button @click="show = false" class="flex-shrink-0 text-white/70 hover:text-white transition-colors p-2 rounded-full hover:bg-white/20">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
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
            swipeThreshold: 120,
            rotationMultiplier: 0.12,
            
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
                
                const rotation = this.currentX * this.rotationMultiplier;
                const opacity = Math.max(0.5, 1 - Math.abs(this.currentX) / 600);
                const scale = Math.max(0.95, 1 - Math.abs(this.currentX) / 1000);
                
                this.card.style.transform = `translateX(${this.currentX}px) translateY(${this.currentY}px) rotate(${rotation}deg) scale(${scale})`;
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
                }, 500);
            },
            
            swipeRight() {
                this.card.classList.add('swiping-right');
                setTimeout(() => {
                    this.$wire.call('swipeRight');
                    this.resetCard();
                    this.card.classList.remove('swiping-right');
                }, 500);
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
                
                if (this.currentX < -60) {
                    leftIndicator?.classList.add('active');
                    rightIndicator?.classList.remove('active');
                } else if (this.currentX > 60) {
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
        }, 500);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            const btn = document.querySelector('.action-button');
            btn?.classList.add('scale-95');
            setTimeout(() => btn?.classList.remove('scale-95'), 100);
            animateSwipe('left', () => @this.call('swipeLeft'));
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            const btns = document.querySelectorAll('.action-button');
            const btn = btns[1];
            btn?.classList.add('scale-95');
            setTimeout(() => btn?.classList.remove('scale-95'), 100);
            animateSwipe('right', () => @this.call('swipeRight'));
        }
    });
    </script>
    @endscript
</x-filament-panels::page>