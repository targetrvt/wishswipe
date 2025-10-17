<x-filament-panels::page>
    {{-- Welcome Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            {{ __('dashboard.welcome_back', ['name' => auth()->user()->name]) }}
        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">
            {{ __('dashboard.welcome_subtitle') }}
        </p>
    </div>

    {{-- Quick Actions Alert --}}
    @if(count($quickActions) > 0)
        <div class="mb-6 space-y-3">
            @foreach($quickActions as $action)
                <a href="{{ $action['url'] }}" 
                   class="block p-4 bg-{{ $action['color'] }}-50 dark:bg-{{ $action['color'] }}-900/20 border border-{{ $action['color'] }}-200 dark:border-{{ $action['color'] }}-800 rounded-xl hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-800 rounded-lg flex items-center justify-center">
                            <x-dynamic-component :component="$action['icon']" class="w-5 h-5 text-{{ $action['color'] }}-600 dark:text-{{ $action['color'] }}-400" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $action['title'] }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $action['description'] }}</p>
                        </div>
                        <x-heroicon-o-chevron-right class="w-5 h-5 text-gray-400 flex-shrink-0" />
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Overview Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Active Listings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-shopping-bag class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('dashboard.metrics.active') }}</span>
            </div>
            <div class="space-y-1">
                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $overview['listings']['active'] }}
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dashboard.metrics.of_total_listings', ['total' => $overview['listings']['total']]) }}
                </p>
            </div>
        </div>

        {{-- Total Views --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-eye class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                </div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('dashboard.metrics.views') }}</span>
            </div>
            <div class="space-y-1">
                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($overview['engagement']['views']) }}
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dashboard.metrics.avg_per_listing', ['avg' => $insights['avg_views']]) }}
                </p>
            </div>
        </div>

        {{-- Matches --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-heart class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                </div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('dashboard.metrics.matches') }}</span>
            </div>
            <div class="space-y-1">
                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $overview['engagement']['matches'] }}
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dashboard.metrics.conversion_rate', ['rate' => $insights['conversion']]) }}
                </p>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-currency-euro class="w-6 h-6 text-green-600 dark:text-green-400" />
                </div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('dashboard.metrics.revenue') }}</span>
            </div>
            <div class="space-y-1">
                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                    €{{ number_format($overview['revenue']['total'], 0) }}
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dashboard.metrics.pending_amount', ['amount' => number_format($overview['revenue']['pending'], 0)]) }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Top Products --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('dashboard.top_products.title') }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('dashboard.top_products.subtitle') }}</p>
            </div>
            <div class="p-6">
                @forelse($topProducts as $product)
                    <div class="flex items-center gap-4 py-3 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-700' : '' }}">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                            @if($product['image'])
                                <img src="{{ Storage::url($product['image']) }}" alt="{{ $product['title'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-heroicon-o-photo class="w-8 h-8 text-gray-400" />
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-900 dark:text-white truncate">{{ $product['title'] }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">€{{ number_format($product['price'], 2) }}</p>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                <x-heroicon-o-eye class="w-4 h-4" />
                                <span>{{ $product['views'] }}</span>
                            </div>
                            <div class="flex items-center gap-1 text-pink-600 dark:text-pink-400">
                                <x-heroicon-o-heart class="w-4 h-4" />
                                <span>{{ $product['likes'] }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <x-heroicon-o-shopping-bag class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                        <p class="text-gray-600 dark:text-gray-400">{{ __('dashboard.top_products.empty') }}</p>
                        <a href="{{ route('filament.app.resources.products.create') }}" class="text-sm text-primary-600 hover:text-primary-700 mt-2 inline-block">
                            {{ __('dashboard.top_products.create_first') }}
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Performance Insights --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('dashboard.performance.title') }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('dashboard.performance.subtitle') }}</p>
            </div>
            <div class="p-6 space-y-6">
                {{-- Conversion Rate --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('dashboard.performance.conversion_rate') }}</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $insights['conversion'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ min($insights['conversion'], 100) }}%"></div>
                    </div>
                </div>

                {{-- Sell-Through Rate --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('dashboard.performance.sell_through_rate') }}</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $insights['sell_through'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full transition-all duration-500" style="width: {{ $insights['sell_through'] }}%"></div>
                    </div>
                </div>

                {{-- Messages --}}
                @if($unreadMessages > 0)
                    <div class="p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg">
                        <div class="flex items-center gap-3">
                            <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 text-orange-600 dark:text-orange-400 flex-shrink-0" />
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ __('dashboard.performance.unread_messages', ['count' => $unreadMessages]) }}</p>
                                <a href="{{ route('filament.app.pages.conversations-page') }}" class="text-sm text-orange-600 dark:text-orange-400 hover:underline">
                                    {{ __('dashboard.performance.view_conversations') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Quick Stats --}}
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $overview['engagement']['swipes'] }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('dashboard.performance.total_swipes') }}</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $overview['listings']['sold'] }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('dashboard.performance.items_sold') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Navigation --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('filament.app.pages.swiping-page') }}" 
           class="group p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <x-heroicon-o-hand-raised class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('dashboard.navigation.start_swiping') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('dashboard.navigation.discover_products') }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route('filament.app.pages.my-listings') }}" 
           class="group p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <x-heroicon-o-shopping-bag class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('dashboard.navigation.my_listings') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('dashboard.navigation.manage_products') }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route('filament.app.resources.products.create') }}" 
           class="group p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <x-heroicon-o-plus-circle class="w-6 h-6 text-green-600 dark:text-green-400" />
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('dashboard.navigation.new_listing') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('dashboard.navigation.create_product') }}</p>
                </div>
            </div>
        </a>
    </div>
</x-filament-panels::page>