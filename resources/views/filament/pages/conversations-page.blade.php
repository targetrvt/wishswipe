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

        /* Negotiate Card Styles */
        .negotiate-card {
            background: white;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            overflow: hidden;
            max-width: 420px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .dark .negotiate-card {
            background: #374151;
            border-color: #4b5563;
        }

        .negotiate-card.own {
            background: #3b82f6;
            border-color: #2563eb;
        }

        .negotiate-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
        }

        .dark .negotiate-header {
            background: #4b5563;
            border-bottom-color: #6b7280;
        }

        .negotiate-card.own .negotiate-header {
            background: rgba(255, 255, 255, 0.1);
            border-bottom-color: rgba(255, 255, 255, 0.2);
        }

        .negotiate-icon {
            width: 20px;
            height: 20px;
            color: #3b82f6;
        }

        .negotiate-card.own .negotiate-icon {
            color: white;
        }

        .negotiate-title {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .dark .negotiate-title {
            color: #f9fafb;
        }

        .negotiate-card.own .negotiate-title {
            color: white;
        }

        .negotiate-body {
            padding: 16px;
        }

        .negotiate-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 12px;
        }

        .negotiate-price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .negotiate-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }

        .dark .negotiate-label {
            color: #9ca3af;
        }

        .negotiate-card.own .negotiate-label {
            color: rgba(255, 255, 255, 0.8);
        }

        .negotiate-price {
            font-size: 16px;
            font-weight: 700;
        }

        .negotiate-price.original {
            color: #6b7280;
            text-decoration: line-through;
        }

        .dark .negotiate-price.original {
            color: #9ca3af;
        }

        .negotiate-card.own .negotiate-price.original {
            color: rgba(255, 255, 255, 0.6);
        }

        .negotiate-price.proposed {
            color: #059669;
        }

        .dark .negotiate-price.proposed {
            color: #34d399;
        }

        .negotiate-card.own .negotiate-price.proposed {
            color: white;
        }

        .negotiate-message {
            margin-top: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            font-size: 13px;
            color: #374151;
            line-height: 1.5;
        }

        .dark .negotiate-message {
            background: #4b5563;
            color: #e5e7eb;
        }

        .negotiate-card.own .negotiate-message {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .negotiate-status {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 12px;
        }

        .negotiate-status svg {
            width: 18px;
            height: 18px;
        }

        .negotiate-status.accepted {
            background: #d1fae5;
            color: #065f46;
        }

        .dark .negotiate-status.accepted {
            background: #064e3b;
            color: #6ee7b7;
        }

        .negotiate-status.declined {
            background: #fee2e2;
            color: #991b1b;
        }

        .dark .negotiate-status.declined {
            background: #7f1d1d;
            color: #fca5a5;
        }

        .negotiate-status.counter {
            background: #fef3c7;
            color: #92400e;
        }

        .dark .negotiate-status.counter {
            background: #78350f;
            color: #fcd34d;
        }

        .negotiate-counter-message {
            margin-top: 8px;
            padding: 10px;
            background: #fef3c7;
            border-radius: 6px;
            font-size: 12px;
            color: #92400e;
        }

        .dark .negotiate-counter-message {
            background: #78350f;
            color: #fcd34d;
        }

        .negotiate-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .negotiate-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .negotiate-btn svg {
            width: 16px;
            height: 16px;
        }

        .negotiate-btn.accept {
            background: #10b981;
            color: white;
        }

        .negotiate-btn.accept:hover {
            background: #059669;
        }

        .negotiate-btn.decline {
            background: #ef4444;
            color: white;
        }

        .negotiate-btn.decline:hover {
            background: #dc2626;
        }

        .negotiate-btn.counter {
            background: #3b82f6;
            color: white;
        }

        .negotiate-btn.counter:hover {
            background: #2563eb;
        }

        /* Counter Offer Modal */
        .counter-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            padding: 20px;
        }

        .counter-modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-width: 480px;
            width: 100%;
            padding: 24px;
        }

        .dark .counter-modal-content {
            background: #1f2937;
        }

        .counter-modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 20px;
        }

        .dark .counter-modal-title {
            color: #f9fafb;
        }

        .counter-modal-field {
            margin-bottom: 16px;
        }

        .counter-modal-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .dark .counter-modal-label {
            color: #d1d5db;
        }

        .counter-modal-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s ease;
        }

        .dark .counter-modal-input {
            background: #374151;
            border-color: #4b5563;
            color: #f9fafb;
        }

        .counter-modal-input:focus {
            border-color: #3b82f6;
        }

        .counter-modal-textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            resize: vertical;
            min-height: 80px;
            outline: none;
            transition: border-color 0.15s ease;
        }

        .dark .counter-modal-textarea {
            background: #374151;
            border-color: #4b5563;
            color: #f9fafb;
        }

        .counter-modal-textarea:focus {
            border-color: #3b82f6;
        }

        .counter-modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .counter-modal-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .counter-modal-btn.cancel {
            background: #f3f4f6;
            color: #374151;
        }

        .counter-modal-btn.cancel:hover {
            background: #e5e7eb;
        }

        .dark .counter-modal-btn.cancel {
            background: #374151;
            color: #d1d5db;
        }

        .dark .counter-modal-btn.cancel:hover {
            background: #4b5563;
        }

        .counter-modal-btn.submit {
            background: #3b82f6;
            color: white;
        }

        .counter-modal-btn.submit:hover {
            background: #2563eb;
        }
    </style>

    <div class="conversations-container" wire:poll.5s>
        <!-- Conversations Sidebar -->
        <div class="conversations-sidebar" x-data="{ mobileVisible: true }">
            <div class="sidebar-header">
                <h2 class="sidebar-title">{{ __('conversations.sidebar.title') }}</h2>
            </div>

            <div class="conversations-list">
                @forelse($this->conversations as $conv)
                    <button
                        wire:click="selectConversation({{ $conv['id'] }})"
                        @click="if(window.innerWidth < 1024) mobileVisible = false"
                        class="conversation-item {{ $selectedConversationId === $conv['id'] ? 'active' : '' }}">
                        
                        <div class="conversation-avatar">
                            @php
                                $avatarUrl = $conv['other_user']->getFilamentAvatarUrl();
                                $userName = $conv['other_user']->name;
                                $initials = strtoupper(substr($userName, 0, 1));
                            @endphp
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $userName }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                            @else
                                <div class="w-10 h-10 rounded-full bg-indigo-800 flex items-center justify-center text-white font-extrabold text-sm border-2 border-gray-300 dark:border-gray-600 shadow-md" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
                                    {{ $initials }}
                                </div>
                            @endif
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
                                @php
                                    $previewText = $conv['last_message']->content;
                                    
                                    // Check if message is a negotiate request
                                    if (is_string($previewText) && strpos($previewText, 'negotiate_request') !== false) {
                                        try {
                                            $decoded = json_decode($previewText, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && isset($decoded['type']) && $decoded['type'] === 'negotiate_request') {
                                                if (isset($decoded['status'])) {
                                                    if ($decoded['status'] === 'accepted') {
                                                        $previewText = '✓ Negotiation accepted';
                                                    } elseif ($decoded['status'] === 'declined') {
                                                        $previewText = '✗ Negotiation declined';
                                                    } elseif ($decoded['status'] === 'counter_offered') {
                                                        $previewText = '↔ Counter offer made';
                                                    }
                                                } else {
                                                    $previewText = 'Price negotiation request';
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            // Keep original content on error
                                        }
                                    }
                                @endphp
                                <p class="conversation-preview {{ $conv['has_unread'] ? 'unread' : '' }}">
                                    {{ Str::limit($previewText, 50) }}
                                </p>
                            @else
                                <p class="conversation-preview" style="font-style: italic;">
                                    {{ __('conversations.conversation.start_conversation') }}
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
                            <h3 class="empty-state-title">{{ __('conversations.empty_states.no_conversations') }}</h3>
                            <p class="empty-state-description">{{ __('conversations.empty_states.no_conversations_description') }}</p>
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
                        
                        @php
                            $headerUser = $this->selectedConversation->getOtherUser(auth()->id());
                            $headerAvatarUrl = $headerUser->getFilamentAvatarUrl();
                            $headerName = $headerUser->name;
                            $headerInitials = strtoupper(substr($headerName, 0, 1));
                        @endphp
                        @if($headerAvatarUrl)
                            <img src="{{ $headerAvatarUrl }}" alt="{{ $headerName }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                        @else
                            <div class="w-10 h-10 rounded-full bg-indigo-800 flex items-center justify-center text-white font-extrabold text-sm border-2 border-gray-300 dark:border-gray-600 shadow-md" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
                                {{ $headerInitials }}
                            </div>
                        @endif
                        
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
                            €{{ number_format($this->selectedConversation->product->price, 2) }}
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
                            $isNegotiateRequest = false;
                            $negotiateData = null;
                            
                            // Only check for negotiate request if content looks like JSON
                            if (!empty($message->content) && is_string($message->content)) {
                                $content = trim($message->content);
                                if (!empty($content) && $content[0] === '{' && strpos($content, 'negotiate_request') !== false) {
                                    try {
                                        $decoded = json_decode($content, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && isset($decoded['type']) && $decoded['type'] === 'negotiate_request') {
                                            $isNegotiateRequest = true;
                                            $negotiateData = $decoded;
                                        }
                                    } catch (\Exception $e) {
                                        // Treat as regular message on any error
                                    }
                                }
                            }
                        @endphp
                        
                        <div class="message-wrapper {{ $isCurrentUser ? 'own' : 'other' }}">
                            @if(!$isCurrentUser)
                                @php
                                    $msgAvatarUrl = $message->user->getFilamentAvatarUrl();
                                    $msgUserName = $message->user->name;
                                    $msgInitials = strtoupper(substr($msgUserName, 0, 1));
                                @endphp
                                @if($msgAvatarUrl)
                                    <img src="{{ $msgAvatarUrl }}" alt="{{ $msgUserName }}" class="w-8 h-8 rounded-full object-cover message-avatar border-2 border-gray-300 dark:border-gray-600">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-indigo-800 flex items-center justify-center text-white font-extrabold text-xs message-avatar border-2 border-gray-300 dark:border-gray-600 shadow-md" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
                                        {{ $msgInitials }}
                                    </div>
                                @endif
                            @endif
                            
                            <div class="message-content">
                                @if($isNegotiateRequest)
                                    {{-- Negotiate Request Card --}}
                                    <div class="negotiate-card {{ $isCurrentUser ? 'own' : 'other' }}">
                                        <div class="negotiate-header">
                                            <svg class="negotiate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            <span class="negotiate-title">Price Negotiation</span>
                                        </div>
                                        
                                        <div class="negotiate-body">
                                            @if(isset($negotiateData['original_price']) && isset($negotiateData['proposed_price']))
                                                <div class="negotiate-info">
                                                    <div class="negotiate-price-row">
                                                        <span class="negotiate-label">Original:</span>
                                                        <span class="negotiate-price original">€{{ number_format($negotiateData['original_price'], 2) }}</span>
                                                    </div>
                                                    <div class="negotiate-price-row">
                                                        <span class="negotiate-label">Proposed:</span>
                                                        <span class="negotiate-price proposed">€{{ number_format($negotiateData['proposed_price'], 2) }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if(isset($negotiateData['message']) && !empty($negotiateData['message']))
                                                <div class="negotiate-message">
                                                    <p>{{ $negotiateData['message'] }}</p>
                                                </div>
                                            @endif
                                            
                                            @if(isset($negotiateData['status']))
                                                @if($negotiateData['status'] === 'accepted')
                                                    <div class="negotiate-status accepted">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>Request Accepted</span>
                                                    </div>
                                                @elseif($negotiateData['status'] === 'declined')
                                                    <div class="negotiate-status declined">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        <span>Request Declined</span>
                                                    </div>
                                                @elseif($negotiateData['status'] === 'counter_offered')
                                                    <div class="negotiate-status counter">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                        </svg>
                                                        <span>Counter Offer: €{{ isset($negotiateData['counter_price']) ? number_format($negotiateData['counter_price'], 2) : '0.00' }}</span>
                                                    </div>
                                                    @if(isset($negotiateData['counter_message']) && !empty($negotiateData['counter_message']))
                                                        <div class="negotiate-counter-message">
                                                            <p>{{ $negotiateData['counter_message'] }}</p>
                                                        </div>
                                                    @endif
                                                @endif
                                            @elseif(!$isCurrentUser)
                                                {{-- Show action buttons for seller --}}
                                                <div class="negotiate-actions">
                                                    <button 
                                                        wire:click="acceptNegotiateRequest({{ $message->id ?? 0 }})"
                                                        class="negotiate-btn accept">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Accept
                                                    </button>
                                                    <button 
                                                        wire:click="declineNegotiateRequest({{ $message->id ?? 0 }})"
                                                        class="negotiate-btn decline">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Decline
                                                    </button>
                                                    <button 
                                                        onclick="window.showCounterOfferModal && window.showCounterOfferModal({{ $message->id ?? 0 }})"
                                                        class="negotiate-btn counter">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                        </svg>
                                                        Counter
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    {{-- Regular Message --}}
                                    <div class="message-bubble {{ $isCurrentUser ? 'own' : 'other' }}">
                                        {{ $message->content }}
                                    </div>
                                @endif
                                
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
                                @php
                                    $currentAvatarUrl = auth()->user()->getFilamentAvatarUrl();
                                    $currentUserName = auth()->user()->name;
                                    $currentInitials = strtoupper(substr($currentUserName, 0, 1));
                                @endphp
                                @if($currentAvatarUrl)
                                    <img src="{{ $currentAvatarUrl }}" alt="{{ $currentUserName }}" class="w-8 h-8 rounded-full object-cover message-avatar border-2 border-gray-300 dark:border-gray-600">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-indigo-800 flex items-center justify-center text-white font-extrabold text-xs message-avatar border-2 border-gray-300 dark:border-gray-600 shadow-md" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
                                        {{ $currentInitials }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-content">
                                <svg class="empty-state-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <h3 class="empty-state-title">{{ __('conversations.empty_states.no_messages') }}</h3>
                                <p class="empty-state-description">{{ __('conversations.empty_states.no_messages_description') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="message-input-container">
                    <form wire:submit.prevent="sendMessage" class="message-input-wrapper">
                        <textarea
                            wire:model="messageContent"
                            class="message-textarea"
                            placeholder="{{ __('conversations.conversation.type_message') }}"
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
                        <h3 class="empty-state-title">{{ __('conversations.empty_states.select_conversation') }}</h3>
                        <p class="empty-state-description">{{ __('conversations.empty_states.select_conversation_description') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Counter Offer Modal --}}
    <div 
        x-data="{ show: false, messageId: null, price: '', message: '' }"
        x-show="show"
        x-cloak
        @counter-offer-modal.window="show = true; messageId = $event.detail.messageId"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="counter-modal"
        style="display: none;"
        @click.self="show = false; price = ''; message = ''"
        @keydown.escape.window="show = false; price = ''; message = ''"
    >
        <div class="counter-modal-content" @click.stop>
            <h3 class="counter-modal-title">Make Counter Offer</h3>
            
            <div class="counter-modal-field">
                <label class="counter-modal-label">Counter Price (€)</label>
                <input 
                    type="number" 
                    x-model="price"
                    step="0.01" 
                    min="0.01"
                    class="counter-modal-input"
                    placeholder="Enter your counter offer price">
            </div>
            
            <div class="counter-modal-field">
                <label class="counter-modal-label">Message (optional)</label>
                <textarea 
                    x-model="message"
                    rows="3"
                    class="counter-modal-textarea"
                    placeholder="Add a message to your counter offer"></textarea>
            </div>
            
            <div class="counter-modal-actions">
                <button 
                    @click="show = false; price = ''; message = ''"
                    class="counter-modal-btn cancel">
                    Cancel
                </button>
                <button 
                    @click="if (price && price > 0) { @this.call('counterNegotiateRequest', messageId, parseFloat(price), message); show = false; price = ''; message = ''; } else { alert('Please enter a valid price'); }"
                    class="counter-modal-btn submit">
                    Send Counter Offer
                </button>
            </div>
        </div>
    </div>

    <script>
        function showCounterOfferModal(messageId) {
            window.dispatchEvent(new CustomEvent('counter-offer-modal', { detail: { messageId } }));
        }
    </script>
</x-filament-panels::page>