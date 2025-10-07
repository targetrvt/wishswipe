<x-filament-panels::page>
    @php
        $totalProducts = \App\Models\Product::where('user_id', auth()->id())->count();
        $activeProducts = \App\Models\Product::where('user_id', auth()->id())->where('status', 'available')->count();
        $soldProducts = \App\Models\Product::where('user_id', auth()->id())->where('status', 'sold')->count();
        $totalViews = \App\Models\Product::where('user_id', auth()->id())->sum('view_count');
        $totalRevenue = \App\Models\Product::where('user_id', auth()->id())->where('status', 'sold')->sum('price');
    @endphp

    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('filament.app.resources.products.create') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Listing
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Total</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Active</span>
                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $activeProducts }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Sold</span>
                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $soldProducts }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Views</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalViews) }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-primary-200 dark:border-primary-800 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-primary-600 dark:text-primary-400">Revenue</span>
                <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-2xl font-semibold text-primary-600 dark:text-primary-400">${{ number_format($totalRevenue, 0) }}</p>
        </div>
    </div>

    {{-- Performance Overview --}}
    @if($totalProducts > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 mb-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4 text-center">
                Performance Metrics
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Active Rate</span>
                        <span class="font-semibold text-green-600 dark:text-green-400">{{ round(($activeProducts / $totalProducts) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full transition-all duration-500" 
                             style="width: {{ round(($activeProducts / $totalProducts) * 100) }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Sold Rate</span>
                        <span class="font-semibold text-blue-600 dark:text-blue-400">{{ round(($soldProducts / $totalProducts) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" 
                             style="width: {{ round(($soldProducts / $totalProducts) * 100) }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Avg. Views</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ round($totalViews / $totalProducts) }} per item</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gray-500 h-2 rounded-full transition-all duration-500" 
                             style="width: {{ min(100, round(($totalViews / $totalProducts) / 10)) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Listings Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                All Listings
            </h2>
        </div>
        
        {{ $this->table }}
    </div>

    {{-- Empty State --}}
    @if($totalProducts === 0)
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8">
            <div class="text-center max-w-md mx-auto">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    No Listings Yet
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Create your first listing to start selling on WishSwipe
                </p>
                <a href="{{ route('filament.app.resources.products.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create First Listing
                </a>
            </div>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Quality Photos</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Use clear, well-lit photos from multiple angles</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Detailed Description</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Include condition, features, and specifications</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="flex-shrink-0 w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Fair Pricing</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Research similar items and price competitively</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Quick Response</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Reply to messages within 24 hours</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>