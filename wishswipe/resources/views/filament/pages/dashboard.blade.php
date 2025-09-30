<x-filament-panels::page>
    @php
        $userId = auth()->id();
        $myProducts = \App\Models\Product::where('user_id', $userId)->count();
        $activeListings = \App\Models\Product::where('user_id', $userId)->where('status', 'available')->count();
        $totalMatches = \App\Models\Matched::where('buyer_id', $userId)->orWhere('seller_id', $userId)->count();
        $totalSwipes = \App\Models\Swipe::where('user_id', $userId)->count();
        $rightSwipes = \App\Models\Swipe::where('user_id', $userId)->where('direction', 'right')->count();
        $recentMatches = \App\Models\Matched::where('buyer_id', $userId)->orWhere('seller_id', $userId)->with(['product', 'buyer', 'seller'])->latest()->limit(5)->get();
    @endphp

    <div class="space-y-6">
        {{-- Welcome Section --}}
        <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-lg shadow-lg p-6 text-white">
            <h2 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-primary-100">Here's what's happening with your WishSwipe account today.</p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- My Products --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">My Products</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $myProducts }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">{{ $activeListings }} active</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-full">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Matches --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Matches</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalMatches }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Connections made</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-full">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Swipes --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Swipes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalSwipes }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">{{ $rightSwipes }} likes</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-full">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Match Rate --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Match Rate</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $rightSwipes > 0 ? round(($totalMatches / $rightSwipes) * 100) : 0 }}%
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">From your likes</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/20 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('filament.app.pages.swiping-page') }}" 
               class="block bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6 group">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-primary-100 dark:bg-primary-900/20 rounded-full group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Start Swiping</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Find your next great deal</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('filament.app.pages.my-listings') }}" 
               class="block bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6 group">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-full group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">My Listings</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Manage your products</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('filament.app.resources.products.create') }}" 
               class="block bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6 group">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-full group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">New Listing</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Sell something new</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Recent Matches --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Matches</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recentMatches as $match)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                    @if($match->product->images)
                                        <img src="{{ Storage::url($match->product->images[0]) }}" 
                                             alt="{{ $match->product->title }}"
                                             class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $match->product->title }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Matched with {{ $match->buyer_id === $userId ? $match->seller->name : $match->buyer->name }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900 dark:text-white">${{ number_format($match->product->price, 2) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $match->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No matches yet. Start swiping to find great deals!</p>
                        <a href="{{ route('filament.app.pages.swiping-page') }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition">
                            Start Swiping
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>