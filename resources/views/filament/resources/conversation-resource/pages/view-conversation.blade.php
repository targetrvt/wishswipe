<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Chat Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <x-filament::avatar 
                        :src="$this->getOtherUser()->getFilamentAvatarUrl()" 
                        size="lg"
                    />
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $this->getOtherUser()->name }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Product: {{ $record->product->title }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-primary-600 dark:text-primary-400">
                        €{{ number_format($record->product->price, 2) }}
                    </p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        {{ ucfirst($record->product->status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Messages Container --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div 
                wire:poll.5s="refreshMessages"
                id="messages-container"
                class="h-[500px] overflow-y-auto p-4 space-y-4"
                x-data="{ 
                    scrollToBottom() { 
                        this.$el.scrollTop = this.$el.scrollHeight; 
                    } 
                }"
                x-init="scrollToBottom()"
                @scroll-to-bottom.window="scrollToBottom()"
            >
                @forelse($messages as $message)
                    @php
                        $isCurrentUser = $message['user_id'] === auth()->id();
                    @endphp
                    
                    <div class="flex {{ $isCurrentUser ? 'justify-end' : 'justify-start' }}">
                        <div class="flex items-end space-x-2 max-w-[70%] {{ $isCurrentUser ? 'flex-row-reverse space-x-reverse' : '' }}">
                            @if(!$isCurrentUser)
                                <x-filament::avatar 
                                    :src="$message['user']['avatar_url'] ?? null" 
                                    size="sm"
                                    class="flex-shrink-0"
                                />
                            @endif
                            
                            <div class="flex flex-col {{ $isCurrentUser ? 'items-end' : 'items-start' }}">
                                <div class="rounded-lg px-4 py-2 {{ $isCurrentUser ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                                    <p class="text-sm whitespace-pre-wrap break-words">{{ $message['content'] }}</p>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($message['created_at'])->format('M d, H:i') }}
                                    @if($isCurrentUser && $message['is_read'])
                                        <span class="ml-1">✓✓</span>
                                    @endif
                                </span>
                            </div>
                            
                            @if($isCurrentUser)
                                <x-filament::avatar 
                                    :src="auth()->user()->getFilamentAvatarUrl()" 
                                    size="sm"
                                    class="flex-shrink-0"
                                />
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No messages yet</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">Start the conversation!</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Message Input --}}
            <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                <form wire:submit.prevent="sendMessage" class="flex space-x-2">
                    <textarea
                        wire:model="messageContent"
                        rows="1"
                        placeholder="Type your message..."
                        class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500 resize-none"
                        x-data="{ resize() { $el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'; } }"
                        x-init="resize()"
                        @input="resize()"
                        @keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); setTimeout(() => { $el.style.height = 'auto'; }, 100); }"
                    ></textarea>
                    
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                    >
                        <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <svg wire:loading wire:target="sendMessage" class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Press Enter to send, Shift + Enter for new line
                </p>
            </div>
        </div>

        {{-- Product Info Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Product Details</h4>
            <div class="flex space-x-4">
                @if($record->product->images)
                    <img 
                        src="{{ Storage::url($record->product->images[0]) }}" 
                        alt="{{ $record->product->title }}"
                        class="w-24 h-24 rounded-lg object-cover"
                    >
                @else
                    <div class="w-24 h-24 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
                
                <div class="flex-1">
                    <h5 class="font-semibold text-gray-900 dark:text-white">{{ $record->product->title }}</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                        {{ $record->product->description }}
                    </p>
                    <div class="flex items-center space-x-2 mt-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $record->product->category->name }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            {{ ucfirst(str_replace('_', ' ', $record->product->condition)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>