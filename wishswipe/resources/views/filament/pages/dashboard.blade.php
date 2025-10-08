<x-filament-panels::page>
    {{-- Hero Header with Gradient Background --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-700 p-8 mb-8 shadow-2xl">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDE0YzMuMzEgMCA2IDIuNjkgNiA2cy0yLjY5IDYtNiA2LTYtMi42OS02LTYgMi42OS02IDYtNnpNNiAzNGMzLjMxIDAgNiAyLjY5IDYgNnMtMi42OSA2LTYgNi02LTIuNjktNi02IDIuNjktNiA2LTZ6bTAgMjBjMy4zMSAwIDYgMi42OSA2IDZzLTIuNjkgNi02IDYtNi0yLjY5LTYtNiAyLjY5LTYgNi02eiIvPjwvZz48L2c+PC9zdmc+')] opacity-40"></div>
        </div>
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-white tracking-tight">
                            Welcome back, {{ auth()->user()->name }}!
                        </h1>
                        <p class="text-white/80 text-sm font-medium mt-1">
                            {{ now()->format('l, F j, Y') }} • Your marketplace is thriving
                        </p>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="relative">
                    <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 flex items-center justify-center">
                        <x-filament::avatar 
                            :src="auth()->user()->getFilamentAvatarUrl()" 
                            size="lg"
                            class="ring-4 ring-white/30"
                        />
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-400 rounded-full border-4 border-purple-600 animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid with Vibrant Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        {{-- Total Products --}}
        <div class="group relative bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl p-6 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full -ml-12 -mb-12"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-4xl font-black text-white">{{ $stats['total_products'] }}</p>
                    </div>
                </div>
                <h3 class="text-white font-bold text-lg mb-1">Total Products</h3>
                <p class="text-white/80 text-sm">{{ $stats['active_products'] }} active • {{ $stats['sold_products'] }} sold</p>
            </div>
        </div>

        {{-- Matches --}}
        <div class="group relative bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl p-6 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full -ml-12 -mb-12"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-4xl font-black text-white">{{ $stats['total_matches'] }}</p>
                        @if($trendData['match_trend'] != 0)
                            <span class="inline-flex items-center text-xs font-bold mt-1 px-2 py-1 rounded-full
                                {{ $trendData['match_trend'] > 0 ? 'bg-emerald-400/30 text-white' : 'bg-red-400/30 text-white' }}">
                                @if($trendData['match_trend'] > 0) ↑ @else ↓ @endif
                                {{ abs($trendData['match_trend']) }}%
                            </span>
                        @endif
                    </div>
                </div>
                <h3 class="text-white font-bold text-lg mb-1">Total Matches</h3>
                <p class="text-white/80 text-sm">{{ $trendData['matches_this_week'] }} matches this week</p>
            </div>
        </div>

        {{-- Match Rate --}}
        <div class="group relative bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full -ml-12 -mb-12"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-4xl font-black text-white">{{ $successRate }}%</p>
                    </div>
                </div>
                <h3 class="text-white font-bold text-lg mb-1">Match Rate</h3>
                <p class="text-white/80 text-sm">{{ $stats['right_swipes'] }} likes given</p>
            </div>
        </div>

        {{-- Profile Views --}}
        <div class="group relative bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full -ml-12 -mb-12"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-4xl font-black text-white">{{ number_format($stats['total_views']) }}</p>
                    </div>
                </div>
                <h3 class="text-white font-bold text-lg mb-1">Profile Views</h3>
                <p class="text-white/80 text-sm">{{ $averageViews }} avg per product</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions & Performance Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Quick Actions --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                <span class="w-2 h-8 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-full"></span>
                Quick Actions
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('filament.app.pages.swiping-page') }}" 
                   class="group relative bg-gradient-to-br from-indigo-50 to-purple-100 dark:from-indigo-950/40 dark:to-purple-900/40 rounded-2xl p-6 border-2 border-indigo-200 dark:border-indigo-800 hover:border-indigo-400 dark:hover:border-indigo-600 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-400/0 to-purple-400/0 group-hover:from-indigo-400/10 group-hover:to-purple-400/10 rounded-2xl transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base mb-1">Start Swiping</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Discover new products</p>
                    </div>
                </a>

                <a href="{{ route('filament.app.pages.my-listings') }}" 
                   class="group relative bg-gradient-to-br from-rose-50 to-pink-100 dark:from-rose-950/40 dark:to-pink-900/40 rounded-2xl p-6 border-2 border-rose-200 dark:border-rose-800 hover:border-rose-400 dark:hover:border-rose-600 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-400/0 to-pink-400/0 group-hover:from-rose-400/10 group-hover:to-pink-400/10 rounded-2xl transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base mb-1">My Listings</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Manage your products</p>
                    </div>
                </a>

                <a href="{{ route('filament.app.resources.products.create') }}" 
                   class="group relative bg-gradient-to-br from-emerald-50 to-teal-100 dark:from-emerald-950/40 dark:to-teal-900/40 rounded-2xl p-6 border-2 border-emerald-200 dark:border-emerald-800 hover:border-emerald-400 dark:hover:border-emerald-600 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/0 to-teal-400/0 group-hover:from-emerald-400/10 group-hover:to-teal-400/10 rounded-2xl transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base mb-1">New Listing</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Sell something new</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Performance Metrics --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                <span class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-teal-600 rounded-full"></span>
                Metrics
            </h2>
            
            <div class="space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Match Success</span>
                        <span class="text-2xl font-black bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">{{ $successRate }}%</span>
                    </div>
                    <div class="relative w-full h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full transition-all duration-1000" 
                             style="width: {{ $successRate }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Active Rate</span>
                        <span class="text-2xl font-black bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">{{ $activeRate }}%</span>
                    </div>
                    <div class="relative w-full h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-1000" 
                             style="width: {{ $activeRate }}%"></div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gradient-to-br from-violet-50 to-purple-100 dark:from-violet-950/40 dark:to-purple-900/40 rounded-2xl border border-violet-200 dark:border-violet-800">
                            <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $averageViews }}</p>
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mt-1">Avg Views</p>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-amber-50 to-orange-100 dark:from-amber-950/40 dark:to-orange-900/40 rounded-2xl border border-amber-200 dark:border-amber-800">
                            <p class="text-2xl font-black text-gray-900 dark:text-white">${{ number_format($stats['total_revenue'], 0) }}</p>
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mt-1">Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Matches --}}
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                <span class="w-2 h-8 bg-gradient-to-b from-rose-500 to-pink-600 rounded-full"></span>
                Recent Matches
            </h2>
            @if($recentMatches->isNotEmpty())
                <a href="{{ route('filament.app.pages.conversations-page') }}" 
                   class="inline-flex items-center gap-2 text-sm font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition-colors group">
                    View all
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endif
        </div>

        @forelse($recentMatches as $match)
            <div class="group flex items-center gap-5 p-5 rounded-2xl hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 dark:hover:from-purple-950/20 dark:hover:to-pink-950/20 border-2 border-transparent hover:border-purple-200 dark:hover:border-purple-800 transition-all mb-4 last:mb-0">
                <div class="relative flex-shrink-0">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 overflow-hidden shadow-lg">
                        @if($match->product->images && count($match->product->images) > 0)
                            <img src="{{ Storage::url($match->product->images[0]) }}" 
                                 alt="{{ $match->product->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-9 h-9 bg-gradient-to-br from-rose-500 to-pink-600 rounded-full flex items-center justify-center ring-4 ring-white dark:ring-gray-800 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-gray-900 dark:text-white truncate text-lg mb-1">
                        {{ $match->product->title }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                        Matched with <span class="font-bold text-purple-600 dark:text-purple-400">{{ $match->buyer_id === auth()->id() ? $match->seller->name : $match->buyer->name }}</span>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $match->created_at->diffForHumans() }}
                    </p>
                </div>

                <div class="text-right flex-shrink-0">
                    <p class="text-2xl font-black bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                        ${{ number_format($match->product->price, 2) }}
                    </p>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-700 dark:from-emerald-900/30 dark:to-teal-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                        {{ ucfirst($match->product->condition) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-3">No matches yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Start swiping to discover amazing products and connect with sellers!
                </p>
                <a href="{{ route('filament.app.pages.swiping-page') }}" 
                   class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-2xl font-bold transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Start Swiping Now
                </a>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>