<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4" wire:poll.5s>
        {{-- Conversations List --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Conversations</h3>
                </div>
                
                <div class="overflow-y-auto max-h-[600px]">
                    @forelse($this->conversations as $conv)
                        <button
                            wire:click="selectConversation({{ $conv['id'] }})"
                            class="w-full px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition border-b border-gray-200 dark:border-gray-700 text-left {{ $selectedConversationId === $conv['id'] ? 'bg-primary-50 dark:bg-primary-900/20' : '' }}"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <x-filament::avatar 
                                        :src="$conv['other_user']->getFilamentAvatarUrl()" 
                                        size="md"
                                    />
                                    @if($conv['has_unread'])
                                        <span class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-primary-600 ring-2 ring-white dark:ring-gray-800"></span>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $conv['other_user']->name }}
                                        </p>
                                        @if($conv['last_message_at'])
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $conv['last_message_at']->diffForHumans(null, true) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <p class="text-xs text-gray-600 dark:text-gray-400 truncate mb-1">
                                        {{ $conv['product']->title }}
                                    </p>
                                    
                                    @if($conv['last_message'])
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate {{ $conv['has_unread'] ? 'font-semibold' : '' }}">
                                            {{ Str::limit($conv['last_message']->content, 40) }}
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-400 dark:text-gray-500 italic">
                                            No messages yet
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="px-4 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No conversations yet</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">Start swiping to match with sellers!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Chat Area --}}
        <div class="lg:col-span-2">
            @if($this->selectedConversation)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    {{-- Chat Header --}}
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <x-filament::avatar 
                                    :src="$this->selectedConversation->getOtherUser(auth()->id())->getFilamentAvatarUrl()" 
                                    size="md"
                                />
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $this->selectedConversation->getOtherUser(auth()->id())->name }}
                                    </h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $this->selectedConversation->product->title }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-primary-600 dark:text-primary-400">
                                    ${{ number_format($this->selectedConversation->product->price, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Messages --}}
                    <div 
                        id="chat-messages"
                        class="h-[450px] overflow-y-auto p-4 space-y-4"
                        x-data="{ 
                            scrollToBottom() { 
                                this.$el.scrollTop = this.$el.scrollHeight; 
                            } 
                        }"
                        x-init="scrollToBottom()"
                        @message-sent.window="setTimeout(() => scrollToBottom(), 100)"
                        @conversation-selected.window="setTimeout(() => scrollToBottom(), 100)"
                    >
                        @forelse($this->selectedConversation->messages as $message)
                            @php
                                $isCurrentUser = $message->user_id === auth()->id();
                            @endphp
                            
                            <div class="flex {{ $isCurrentUser ? 'justify-end' : 'justify-start' }}">
                                <div class="flex items-end space-x-2 max-w-[70%] {{ $isCurrentUser ? 'flex-row-reverse space-x-reverse' : '' }}">
                                    @if(!$isCurrentUser)
                                        <x-filament::avatar 
                                            :src="$message->user->getFilamentAvatarUrl()" 
                                            size="sm"
                                            class="flex-shrink-0"
                                        />
                                    @endif
                                    
                                    <div class="flex flex-col {{ $isCurrentUser ? 'items-end' : 'items-start' }}">
                                        <div class="rounded-lg px-4 py-2 {{ $isCurrentUser ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                                            <p class="text-sm whitespace-pre-wrap break-words">{{ $message->content }}</p>
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $message->created_at->format('M d, H:i') }}
                                            @if($isCurrentUser && $message->is_read)
                                                <span class="ml-1">✓✓</span>
                                            @endif
                                        </span>
                                    </div>
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
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow h-[600px] flex items-center justify-center">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Select a conversation to start messaging</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>