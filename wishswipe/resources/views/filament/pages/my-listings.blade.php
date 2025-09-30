<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @php
                $stats = [
                    [
                        'label' => 'Total Listings',
                        'value' => \App\Models\Product::where('user_id', auth()->id())->count(),
                        'icon' => 'heroicon-o-shopping-bag',
                        'color' => 'primary',
                    ],
                    [
                        'label' => 'Available',
                        'value' => \App\Models\Product::where('user_id', auth()->id())->where('status', 'available')->count(),
                        'icon' => 'heroicon-o-check-circle',
                        'color' => 'success',
                    ],
                    [
                        'label' => 'Sold',
                        'value' => \App\Models\Product::where('user_id', auth()->id())->where('status', 'sold')->count(),
                        'icon' => 'heroicon-o-currency-dollar',
                        'color' => 'warning',
                    ],
                    [
                        'label' => 'Total Views',
                        'value' => \App\Models\Product::where('user_id', auth()->id())->sum('view_count'),
                        'icon' => 'heroicon-o-eye',
                        'color' => 'info',
                    ],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stat['value'] }}</p>
                        </div>
                        <div class="p-3 bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-900/20 rounded-full">
                            <x-filament::icon 
                                :icon="$stat['icon']" 
                                class="w-6 h-6 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400"
                            />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('filament.app.resources.products.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Listing
                </a>
                
                <button 
                    wire:click="$refresh"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        {{-- Listings Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>