<x-filament-panels::page>
    @php
        $userId = auth()->id();
        $totalProducts = \App\Models\Product::where('user_id', $userId)->count();
        $activeProducts = \App\Models\Product::where('user_id', $userId)->where('status', 'available')->count();
        $soldProducts = \App\Models\Product::where('user_id', $userId)->where('status', 'sold')->count();
        $totalSwipes = \App\Models\Swipe::where('user_id', $userId)->count();
        $rightSwipes = \App\Models\Swipe::where('user_id', $userId)->where('direction', 'right')->count();
        $totalMatches = \App\Models\Matched::where('buyer_id', $userId)->orWhere('seller_id', $userId)->count();
        $totalViews = \App\Models\Product::where('user_id', $userId)->sum('view_count');
        $recentMatches = \App\Models\Matched::where('buyer_id', $userId)
            ->orWhere('seller_id', $userId)
            ->with(['product', 'buyer', 'seller'])
            ->latest()
            ->limit(3)
            ->get();
    @endphp

    {{-- Hero Welcome Section --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-8 mb-6">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">
                    Welcome back, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="text-white/90 text-lg">
                    {{ now()->format('l, F j, Y') }}
                </p>
            </div>
            <div class="hidden md:block">
                <div class="w-32 h-32 rounded-full bg-white/10 backdrop-blur-lg flex items-center justify-center">
                    <x-filament::avatar 
                        :src="auth()->user()->getFilamentAvatarUrl()" 
                        size="xl"
                        class="ring-4 ring-white/30"
                    />
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Total Products --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="text-3xl font-black">{{ $totalProducts }}</span>
            </div>
            <h3 class="text-lg font-semibold opacity-90">Total Products</h3>
            <p class="text-sm opacity-75 mt-1">{{ $activeProducts }} active listings</p>
        </div>

        {{-- Matches --}}
        <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <span class="text-3xl font-black">{{ $totalMatches }}</span>
            </div>
            <h3 class="text-lg font-semibold opacity-90">Matches</h3>
            <p class="text-sm opacity-75 mt-1">Connections made</p>
        </div>

        {{-- Swipes --}}
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                    </svg>
                </div>
                <span class="text-3xl font-black">{{ $totalSwipes }}</span>
            </div>
            <h3 class="text-lg font-semibold opacity-90">Total Swipes</h3>
            <p class="text-sm opacity-75 mt-1">{{ $rightSwipes }} likes given</p>
        </div>

        {{-- Views --}}
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white/20 rounded-xl p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span class="text-3xl font-black">{{ number_format($totalViews) }}</span>
            </div>
            <h3 class="text-lg font-semibold opacity-90">Profile Views</h3>
            <p class="text-sm opacity-75 mt-1">Total visibility</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Quick Actions --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <div class="w-2 h-8 bg-gradient-to-b from-indigo-500 to-purple-500 rounded-full"></div>
                    Quick Actions
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('filament.app.pages.swiping-page') }}" 
                       class="group relative overflow-hidden bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl p-6 border-2 border-indigo-200 dark:border-indigo-800 hover:border-indigo-400 dark:hover:border-indigo-600 transition-all">
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white mb-1">Start Swiping</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Discover new products</p>
                        </div>
                    </a>

                    <a href="{{ route('filament.app.pages.my-listings') }}" 
                       class="group relative overflow-hidden bg-gradient-to-br from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 rounded-xl p-6 border-2 border-rose-200 dark:border-rose-800 hover:border-rose-400 dark:hover:border-rose-600 transition-all">
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-rose-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white mb-1">My Listings</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Manage products</p>
                        </div>
                    </a>

                    <a href="{{ route('filament.app.resources.products.create') }}" 
                       class="group relative overflow-hidden bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl p-6 border-2 border-emerald-200 dark:border-emerald-800 hover:border-emerald-400 dark:hover:border-emerald-600 transition-all">
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white mb-1">New Listing</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Sell something</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Activity Overview --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full"></div>
                Activity
            </h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Success Rate</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Matches / Likes</p>
                    </div>
                    <div class="text-2xl font-black text-emerald-600">
                        {{ $rightSwipes > 0 ? round(($totalMatches / $rightSwipes) * 100) : 0 }}%
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Avg. Views</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Per product</p>
                    </div>
                    <div class="text-2xl font-black text-blue-600">
                        {{ $totalProducts > 0 ? round($totalViews / $totalProducts) : 0 }}
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Active Rate</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Available items</p>
                    </div>
                    <div class="text-2xl font-black text-amber-600">
                        {{ $totalProducts > 0 ? round(($activeProducts / $totalProducts) * 100) : 0 }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Matches --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <div class="w-2 h-8 bg-gradient-to-b from-rose-500 to-pink-500 rounded-full"></div>
                Recent Matches
            </h2>
            @if($recentMatches->isNotEmpty())
                <a href="{{ route('filament.app.pages.conversations-page') }}" 
                   class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">
                    View all →
                </a>
            @endif
        </div>

        @forelse($recentMatches as $match)
            <div class="group flex items-center gap-4 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors mb-3 last:mb-0">
                <div class="relative flex-shrink-0">
                    <div class="w-20 h-20 rounded-xl bg-gray-100 dark:bg-gray-700 overflow-hidden">
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
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-rose-500 rounded-full flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-gray-900 dark:text-white truncate mb-1">
                        {{ $match->product->title }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                        Matched with <span class="font-semibold">{{ $match->buyer_id === $userId ? $match->seller->name : $match->buyer->name }}</span>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500">
                        {{ $match->created_at->diffForHumans() }}
                    </p>
                </div>

                <div class="text-right flex-shrink-0">
                    <p class="text-xl font-black text-indigo-600 dark:text-indigo-400">
                        ${{ number_format($match->product->price, 2) }}
                    </p>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 mt-1">
                        {{ ucfirst($match->product->condition) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">No matches yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Start swiping to connect with sellers!</p>
                <a href="{{ route('filament.app.pages.swiping-page') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white rounded-xl font-semibold transition-all transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                    </svg>
                    Start Swiping Now
                </a>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>