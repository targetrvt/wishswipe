<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Filter Section --}}
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <select wire:model.live="selectedCategory" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">All Categories</option>
                    @foreach(\App\Models\Category::where('is_active', true)->get() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            @if($selectedCategory)
                <button wire:click="resetFilters" class="ml-4 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                    Clear Filters
                </button>
            @endif
        </div>

        {{-- Swipe Card Section --}}
        <div class="flex justify-center items-center min-h-[600px]">
            @if($noMoreProducts)
                <div class="text-center">
                    <div class="mb-4">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No More Products</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">You've seen all available products in this category.</p>
                    <button wire:click="loadProducts" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg">
                        Refresh
                    </button>
                </div>
            @elseif($currentProduct)
                <div class="w-full max-w-md">
                    {{-- Product Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden" x-data="{ imageIndex: 0 }">
                        {{-- Images --}}
                        <div class="relative h-96 bg-gray-200 dark:bg-gray-700">
                            @if($currentProduct['images'])
                                @foreach($currentProduct['images'] as $index => $image)
                                    <img 
                                        x-show="imageIndex === {{ $index }}"
                                        src="{{ Storage::url($image) }}" 
                                        alt="{{ $currentProduct['title'] }}"
                                        class="w-full h-full object-cover"
                                    >
                                @endforeach
                                
                                {{-- Image Navigation --}}
                                @if(count($currentProduct['images']) > 1)
                                    <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
                                        @foreach($currentProduct['images'] as $index => $image)
                                            <button 
                                                @click="imageIndex = {{ $index }}"
                                                class="w-2 h-2 rounded-full transition-all"
                                                :class="imageIndex === {{ $index }} ? 'bg-white w-8' : 'bg-white/50'"
                                            ></button>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <svg class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Product Info --}}
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $currentProduct['title'] }}
                                </h2>
                                <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                    ${{ number_format($currentProduct['price'], 2) }}
                                </span>
                            </div>

                            <div class="flex items-center space-x-2 mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $currentProduct['category']['name'] ?? 'Uncategorized' }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ ucfirst(str_replace('_', ' ', $currentProduct['condition'])) }}
                                </span>
                            </div>

                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                {{ $currentProduct['description'] }}
                            </p>

                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $currentProduct['location'] ?? 'Location not specified' }}
                            </div>

                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-2">
                                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Sold by {{ $currentProduct['user']['name'] ?? 'Unknown' }}
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-center items-center space-x-8 mt-8">
                        <button 
                            wire:click="swipeLeft"
                            class="group relative flex items-center justify-center w-16 h-16 bg-red-500 hover:bg-red-600 rounded-full shadow-lg transform transition hover:scale-110 active:scale-95"
                        >
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="absolute -bottom-8 text-sm text-gray-600 dark:text-gray-400">Pass</span>
                        </button>

                        <button 
                            wire:click="swipeRight"
                            class="group relative flex items-center justify-center w-20 h-20 bg-green-500 hover:bg-green-600 rounded-full shadow-xl transform transition hover:scale-110 active:scale-95"
                        >
                            <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="absolute -bottom-8 text-sm text-gray-600 dark:text-gray-400">Interested</span>
                        </button>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">Loading products...</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Match Notification --}}
    <div 
        x-data="{ show: false }"
        @show-match-notification.window="show = true; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
        style="display: none;"
    >
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-medium">It's a match! Check your conversations.</span>
        </div>
    </div>
</x-filament-panels::page>