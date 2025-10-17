<div class="space-y-4">
    {{-- Product Images --}}
    @if($record->images && is_array($record->images) && count($record->images) > 0)
        <div>
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Images</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($record->images as $image)
                    <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800">
                        <img src="{{ Storage::url($image) }}" 
                             alt="{{ $record->title }}"
                             class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Product Details --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Price</dt>
            <dd class="mt-1 text-xl font-bold text-green-600 dark:text-green-400">
                ${{ number_format($record->price, 2) }}
            </dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</dt>
            <dd class="mt-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    {{ $record->category->name ?? 'Uncategorized' }}
                </span>
            </dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Condition</dt>
            <dd class="mt-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($record->condition === 'new') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @elseif($record->condition === 'like_new') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                    @else bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $record->condition)) }}
                </span>
            </dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
            <dd class="mt-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($record->status === 'available') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @elseif($record->status === 'reserved') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                    @endif">
                    {{ ucfirst($record->status) }}
                </span>
            </dd>
        </div>

        @if($record->location)
        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center gap-1">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ $record->location }}
            </dd>
        </div>
        @endif

        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Views</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center gap-1">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{ number_format($record->view_count) }}
            </dd>
        </div>
    </div>

    {{-- Description --}}
    <div>
        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</dt>
        <dd class="text-sm text-gray-900 dark:text-white whitespace-pre-line bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            {{ $record->description }}
        </dd>
    </div>

    {{-- Metadata --}}
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            <span>Created: {{ $record->created_at->format('M d, Y') }}</span>
            <span>{{ $record->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>