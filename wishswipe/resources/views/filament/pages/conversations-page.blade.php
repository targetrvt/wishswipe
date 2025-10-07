<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6" wire:poll.5s>
        {{-- Conversations List --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden h-[calc(100vh-12rem)]">
                <div class="sticky top-0 z-10 px-4 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Conversations
                    </h3>
                </div>
                
                <div class="overflow-y-auto h-[calc(100vh-18rem)]">
                    @forelse($this->conversations as $conv)
                        <button
                            wire:click="selectConversation({{ $conv['id'] }})"
                            class="w-full px-4 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all border-b border-gray-200 dark:border-gray-700 text-left group {{ $selectedConversationId === $conv['id'] ? 'bg-primary-50 dark:bg-primary-900/20 border-l-4 border-l-primary-600' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="relative flex-shrink-0">
                                    <x-filament::avatar 
                                        :src="$conv['other_user']->getFilamentAvatarUrl()" 
                                        size="lg"
                                    />
                                    @if($conv['has_unread'])
                                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-4 w-4 bg-primary-600 ring-2 ring-white dark:ring-gray-800"></span>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate {{ $conv['has_unread'] ? 'font-bold' : '' }}">
                                            {{ $conv['other_user']->name }}
                                        </p>
                                        @if($conv['last_message_at'])
                                            <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0 ml-2">
                                                {{ $conv['last_message_at']->diffForHumans(null, true, true) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <p class="text-xs text-primary-600 dark:text-primary-400 truncate mb-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        {{ $conv['product']->title }}
                                    </p>
                                    
                                    @if($conv['last_message'])
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate {{ $conv['has_unread'] ? 'font-semibold text-gray-900 dark:text-white' : '' }}">
                                            {{ Str::limit($conv['last_message']->content, 35) }}
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
                        <div class="px-4 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-2">No conversations yet</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Start swiping to match with sellers!</p>
                            <a href="{{ route('filament.app.pages.swiping-page') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                                </svg>
                                Start Swiping
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Chat Area --}}
        <div class="lg:col-span-2">
            @if($this->selectedConversation)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden h-[calc(100vh-12rem)] flex flex-col">
                    
                    {{-- Chat Header --}}
                    <div class="sticky top-0 z-10 px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <x-filament::avatar 
                                    :src="$this->selectedConversation->getOtherUser(auth()->id())->getFilamentAvatarUrl()" 
                                    size="lg"
                                />
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $this->selectedConversation->getOtherUser(auth()->id())->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        {{ $this->selectedConversation->product->title }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-lg font-bold text-primary-600 dark:text-primary-400">
                                    ${{ number_format($this->selectedConversation->product->price, 2) }}
                                </p>
                                <x-filament::badge color="success" size="sm">
                                    {{ ucfirst($this->selectedConversation->product->status) }}
                                </x-filament::badge>
                            </div>
                        </div>
                    </div>

                    {{-- Messages Container --}}
                    <div 
                        id="chat-messages"
                        class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4"
                        x-data="{ 
                            scrollToBottom() { 
                                this.$el.scrollTop = this.$el.scrollHeight; 
                            } 
                        }"
                        x-init="scrollToBottom()"
                        @message-sent.window="setTimeout(() => scrollToBottom(), 100)"
                        @conversation-selected.window="setTimeout(() => scrollToBottom(), 100)">
                        
                        @forelse($this->selectedConversation->messages as $message)
                            @php
                                $isCurrentUser = $message->user_id === auth()->id();
                            @endphp
                            
                            <div class="flex {{ $isCurrentUser ? 'justify-end' : 'justify-start' }}">
                                <div class="flex items-end gap-2 max-w-[85%] sm:max-w-[70%] {{ $isCurrentUser ? 'flex-row-reverse' : '' }}">
                                    @if(!$isCurrentUser)
                                        <x-filament::avatar 
                                            :src="$message->user->getFilamentAvatarUrl()" 
                                            size="sm"
                                            class="flex-shrink-0"
                                        />
                                    @endif
                                    
                                    <div class="flex flex-col {{ $isCurrentUser ? 'items-end' : 'items-start' }}">
                                        <div class="rounded-2xl px-4 py-2.5 shadow-sm {{ $isCurrentUser ? 'bg-primary-600 text-white rounded-br-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-bl-sm' }}">
                                            <p class="text-sm whitespace-pre-wrap break-words leading-relaxed">{{ $message->content }}</p>
                                        </div>
                                        <div class="flex items-center gap-1 mt-1 px-1">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $message->created_at->format('g:i A') }}
                                            </span>
                                            @if($isCurrentUser && $message->is_read)
                                                <svg class="w-3 h-3 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center max-w-sm">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No messages yet</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Start the conversation by sending a message!</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Message Input --}}
                    <div class="sticky bottom-0 border-t border-gray-200 dark:border-gray-700 p-4 sm:p-6 bg-white dark:bg-gray-800">
                        <form wire:submit.prevent="sendMessage" class="flex gap-3">
                            <div class="flex-1 relative">
                                <textarea
                                    wire:model="messageContent"
                                    rows="1"
                                    placeholder="Type your message..."
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 resize-none pr-12 py-3"
                                    x-data="{ resize() { $el.style.height = '48px'; $el.style.height = Math.min($el.scrollHeight, 120) + 'px'; } }"
                                    x-init="resize()"
                                    @input="resize()"
                                    @keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); setTimeout(() => { $el.style.height = '48px'; }, 100); }"
                                ></textarea>
                                
                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="absolute right-2 bottom-2 p-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed hover:scale-105 active:scale-95">
                                    <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    <svg wire:loading wire:target="sendMessage" class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Press Enter to send • Shift + Enter for new line
                        </p>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 h-[calc(100vh-12rem)] flex items-center justify-center">
                    <div class="text-center max-w-md mx-auto px-4">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/20 dark:to-primary-800/20 mb-6">
                            <svg class="w-10 h-10 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Select a conversation</h3>
                        <p class="text-gray-600 dark:text-gray-400">Choose a conversation from the list to start messaging</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>