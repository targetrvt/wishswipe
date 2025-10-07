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
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">
                    My Listings
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Manage and track all your products
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button wire:click="$refresh"
                        class="flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-2 border-gray-300 dark:border-gray-600 hover:border-indigo-500 dark:hover:border-indigo-500 rounded-xl font-bold transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </button>
                <a href="{{ route('filament.app.resources.products.create') }}"
                   class="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white rounded-xl font-bold transition-all transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Listing
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Total</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Active</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $activeProducts }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Sold</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $soldProducts }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Views</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalViews) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-white/80 font-semibold">Revenue</p>
                    <p class="text-2xl font-black text-white">${{ number_format($totalRevenue, 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Performance Overview --}}
    @if($totalProducts > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h2 class="text-lg font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <div class="w-1 h-6 bg-gradient-to-b from-indigo-500 to-purple-500 rounded-full"></div>
                Performance Metrics
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Active Rate</span>
                        <span class="font-black text-emerald-600">{{ round(($activeProducts / $totalProducts) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full transition-all duration-500" 
                             style="width: {{ round(($activeProducts / $totalProducts) * 100) }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Sold Rate</span>
                        <span class="font-black text-amber-600">{{ round(($soldProducts / $totalProducts) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-2 rounded-full transition-all duration-500" 
                             style="width: {{ round(($soldProducts / $totalProducts) * 100) }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Avg. Views</span>
                        <span class="font-black text-blue-600">{{ round($totalViews / $totalProducts) }} per item</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2 rounded-full transition-all duration-500" 
                             style="width: {{ min(100, round(($totalViews / $totalProducts) / 10)) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Listings Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                <div class="w-1 h-6 bg-gradient-to-b from-indigo-500 to-purple-500 rounded-full"></div>
                All Listings
            </h2>
        </div>
        
        {{ $this->table }}
    </div>

    {{-- Empty State / Tips --}}
    @if($totalProducts === 0)
        <div class="mt-6 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-2xl p-8 border-2 border-indigo-200 dark:border-indigo-800">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">
                        Start Selling Today!
                    </h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        Create your first listing and reach thousands of potential buyers on WishSwipe.
                    </p>
                    <a href="{{ route('filament.app.resources.products.create') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white rounded-xl font-bold transition-all transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create First Listing
                    </a>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start gap-3 p-4 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">Quality Photos</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Use clear, well-lit photos from multiple angles</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">Detailed Description</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Include condition, features, and specifications</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                    <div class="flex-shrink-0 w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">Fair Pricing</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Research similar items and price competitively</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                    <div class="flex-shrink-0 w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">Quick Response</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Reply to messages within 24 hours</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>