<x-filament-panels::page>
    <style>
        .conversations-container {
            height: calc(100vh - 8rem);
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 0;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 1024px) {
            .conversations-container {
                grid-template-columns: 1fr;
            }
            .conversations-sidebar {
                display: none;
            }
            .conversations-sidebar.mobile-visible {
                display: flex;
            }
            .chat-area.mobile-hidden {
                display: none;
            }
        }

        .conversations-sidebar {
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e5e7eb;
            background: #fafafa;
        }

        .dark .conversations-sidebar {
            background: #111827;
            border-right-color: #374151;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            background: white;
        }

        .dark .sidebar-header {
            background: #1f2937;
            border-bottom-color: #374151;
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .dark .sidebar-title {
            color: #f9fafb;
        }

        .conversations-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .conversation-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 4px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.15s ease;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
        }

        .conversation-item:hover {
            background: #f3f4f6;
        }

        .dark .conversation-item:hover {
            background: #374151;
        }

        .conversation-item.active {
            background: white;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .dark .conversation-item.active {
            background: #1f2937;
        }

        .conversation-avatar {
            position: relative;
            flex-shrink: 0;
        }

        .unread-indicator {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            background: #3b82f6;
            border-radius: 50%;
            border: 2px solid white;
        }

        .dark .unread-indicator {
            border-color: #1f2937;
        }

        .conversation-details {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .conversation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .conversation-name {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dark .conversation-name {
            color: #f9fafb;
        }

        .conversation-time {
            font-size: 12px;
            color: #6b7280;
            flex-shrink: 0;
        }

        .dark .conversation-time {
            color: #9ca3af;
        }

        .conversation-product {
            font-size: 12px;
            color: #6b7280;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dark .conversation-product {
            color: #9ca3af;
        }

        .conversation-preview {
            font-size: 13px;
            color: #6b7280;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dark .conversation-preview {
            color: #9ca3af;
        }

        .conversation-item.active .conversation-preview,
        .conversation-item .conversation-preview.unread {
            color: #111827;
            font-weight: 500;
        }

        .dark .conversation-item.active .conversation-preview,
        .dark .conversation-item .conversation-preview.unread {
            color: #f9fafb;
        }

        .chat-area {
            display: flex;
            flex-direction: column;
            background: white;
        }

        .dark .chat-area {
            background: #1f2937;
        }

        .chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            background: white;
        }

        .dark .chat-header {
            background: #1f2937;
            border-bottom-color: #374151;
        }

        .chat-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .chat-user-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
            flex: 1;
        }

        .chat-user-name {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dark .chat-user-name {
            color: #f9fafb;
        }

        .chat-product-name {
            font-size: 13px;
            color: #6b7280;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dark .chat-product-name {
            color: #9ca3af;
        }

        .chat-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: #3b82f6;
        }

        .dark .product-price {
            color: #60a5fa;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            background: #fafafa;
        }

        .dark .messages-container {
            background: #111827;
        }

        .message-wrapper {
            display: flex;
            gap: 8px;
            align-items: flex-end;
        }

        .message-wrapper.own {
            flex-direction: row-reverse;
        }

        .message-avatar {
            flex-shrink: 0;
        }

        .message-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
            max-width: 60%;
        }

        .message-wrapper.own .message-content {
            align-items: flex-end;
        }

        .message-bubble {
            padding: 10px 14px;
            border-radius: 16px;
            word-wrap: break-word;
            font-size: 14px;
            line-height: 1.5;
        }

        .message-bubble.other {
            background: white;
            color: #111827;
            border-bottom-left-radius: 4px;
        }

        .dark .message-bubble.other {
            background: #374151;
            color: #f9fafb;
        }

        .message-bubble.own {
            background: #3b82f6;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message-time {
            font-size: 11px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .message-wrapper.own .message-time {
            flex-direction: row-reverse;
        }

        .read-indicator {
            width: 14px;
            height: 14px;
            color: #3b82f6;
        }

        .message-input-container {
            padding: 16px 20px;
            border-top: 1px solid #e5e7eb;
            background: white;
        }

        .dark .message-input-container {
            background: #1f2937;
            border-top-color: #374151;
        }

        .message-input-wrapper {
            display: flex;
            align-items: flex-end;
            gap: 12px;
        }

        .message-textarea {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            resize: none;
            min-height: 44px;
            max-height: 120px;
            outline: none;
            transition: border-color 0.15s ease;
        }

        .dark .message-textarea {
            background: #374151;
            border-color: #4b5563;
            color: #f9fafb;
        }

        .message-textarea:focus {
            border-color: #3b82f6;
        }

        .send-button {
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #3b82f6;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s ease;
        }

        .send-button:hover {
            background: #2563eb;
        }

        .send-button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .empty-state {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .empty-state-content {
            text-align: center;
            max-width: 320px;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            color: #d1d5db;
        }

        .dark .empty-state-icon {
            color: #4b5563;
        }

        .empty-state-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 8px;
        }

        .dark .empty-state-title {
            color: #f9fafb;
        }

        .empty-state-description {
            font-size: 14px;
            color: #6b7280;
        }

        .dark .empty-state-description {
            color: #9ca3af;
        }

        .back-button {
            display: none;
            padding: 8px;
            border-radius: 8px;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #6b7280;
        }

        .back-button:hover {
            background: #f3f4f6;
        }

        .dark .back-button:hover {
            background: #374151;
        }

        @media (max-width: 1024px) {
            .back-button {
                display: block;
            }
        }

        /* Scrollbar Styling */
        .conversations-list::-webkit-scrollbar,
        .messages-container::-webkit-scrollbar {
            width: 6px;
        }

        .conversations-list::-webkit-scrollbar-track,
        .messages-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .conversations-list::-webkit-scrollbar-thumb,
        .messages-container::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        .dark .conversations-list::-webkit-scrollbar-thumb,
        .dark .messages-container::-webkit-scrollbar-thumb {
            background: #4b5563;
        }
    </style>

    <div class="conversations-container" wire:poll.5s>
        <!-- Conversations Sidebar -->
        <div class="conversations-sidebar" x-data="{ mobileVisible: true }">
            <div class="sidebar-header">
                <h2 class="sidebar-title">Messages</h2>
            </div>

            <div class="conversations-list">
                @forelse($this->conversations as $conv)
                    <button
                        wire:click="selectConversation({{ $conv['id'] }})"
                        @click="if(window.innerWidth < 1024) mobileVisible = false"
                        class="conversation-item {{ $selectedConversationId === $conv['id'] ? 'active' : '' }}">
                        
                        <div class="conversation-avatar">
                            <x-filament::avatar 
                                :src="$conv['other_user']->getFilamentAvatarUrl()" 
                                size="md"
                            />
                            @if($conv['has_unread'])
                                <span class="unread-indicator"></span>
                            @endif
                        </div>

                        <div class="conversation-details">
                            <div class="conversation-header">
                                <span class="conversation-name">{{ $conv['other_user']->name }}</span>
                                @if($conv['last_message_at'])
                                    <span class="conversation-time">
                                        {{ $conv['last_message_at']->diffForHumans(null, true, true) }}
                                    </span>
                                @endif
                            </div>
                            
                            <p class="conversation-product">{{ $conv['product']->title }}</p>
                            
                            @if($conv['last_message'])
                                <p class="conversation-preview {{ $conv['has_unread'] ? 'unread' : '' }}">
                                    {{ Str::limit($conv['last_message']->content, 50) }}
                                </p>
                            @else
                                <p class="conversation-preview" style="font-style: italic;">
                                    Start the conversation
                                </p>
                            @endif
                        </div>
                    </button>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-content">
                            <svg class="empty-state-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <h3 class="empty-state-title">No conversations</h3>
                            <p class="empty-state-description">Match with sellers to start chatting</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        @if($this->selectedConversation)
            <div class="chat-area" x-data="{ mobileHidden: false }">
                <div class="chat-header">
                    <div class="chat-header-left">
                        <button class="back-button" @click="mobileVisible = true; mobileHidden = true">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        
                        <x-filament::avatar 
                            :src="$this->selectedConversation->getOtherUser(auth()->id())->getFilamentAvatarUrl()" 
                            size="md"
                        />
                        
                        <div class="chat-user-info">
                            <span class="chat-user-name">
                                {{ $this->selectedConversation->getOtherUser(auth()->id())->name }}
                            </span>
                            <span class="chat-product-name">
                                {{ $this->selectedConversation->product->title }}
                            </span>
                        </div>
                    </div>

                    <div class="chat-header-right">
                        <span class="product-price">
                            ${{ number_format($this->selectedConversation->product->price, 2) }}
                        </span>
                    </div>
                </div>

                <div 
                    class="messages-container"
                    id="messages-scroll"
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
                        
                        <div class="message-wrapper {{ $isCurrentUser ? 'own' : 'other' }}">
                            @if(!$isCurrentUser)
                                <x-filament::avatar 
                                    :src="$message->user->getFilamentAvatarUrl()" 
                                    size="sm"
                                    class="message-avatar"
                                />
                            @endif
                            
                            <div class="message-content">
                                <div class="message-bubble {{ $isCurrentUser ? 'own' : 'other' }}">
                                    {{ $message->content }}
                                </div>
                                <div class="message-time">
                                    <span>{{ $message->created_at->format('g:i A') }}</span>
                                    @if($isCurrentUser && $message->is_read)
                                        <svg class="read-indicator" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            @if($isCurrentUser)
                                <x-filament::avatar 
                                    :src="auth()->user()->getFilamentAvatarUrl()" 
                                    size="sm"
                                    class="message-avatar"
                                />
                            @endif
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-content">
                                <svg class="empty-state-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <h3 class="empty-state-title">No messages yet</h3>
                                <p class="empty-state-description">Send a message to start the conversation</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="message-input-container">
                    <form wire:submit.prevent="sendMessage" class="message-input-wrapper">
                        <textarea
                            wire:model="messageContent"
                            class="message-textarea"
                            placeholder="Type a message..."
                            rows="1"
                            x-data="{ 
                                resize() { 
                                    $el.style.height = '44px'; 
                                    $el.style.height = Math.min($el.scrollHeight, 120) + 'px'; 
                                } 
                            }"
                            x-init="resize()"
                            @input="resize()"
                            @keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); setTimeout(() => { $el.style.height = '44px'; }, 100); }"
                        ></textarea>
                        
                        <button
                            type="submit"
                            class="send-button"
                            wire:loading.attr="disabled">
                            <svg wire:loading.remove wire:target="sendMessage" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <svg wire:loading wire:target="sendMessage" width="20" height="20" class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="chat-area">
                <div class="empty-state">
                    <div class="empty-state-content">
                        <svg class="empty-state-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <h3 class="empty-state-title">Select a conversation</h3>
                        <p class="empty-state-description">Choose a conversation to view messages</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>