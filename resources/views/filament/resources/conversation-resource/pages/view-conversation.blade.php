<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Chat Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    @php
                        $headerUser = $this->getOtherUser();
                        $headerAvatarUrl = $headerUser->getFilamentAvatarUrl();
                        $headerName = $headerUser->name;
                        $headerInitials = strtoupper(substr($headerName, 0, 1));
                    @endphp
                    @if($headerAvatarUrl)
                        <img src="{{ $headerAvatarUrl }}" alt="{{ $headerName }}" class="w-12 h-12 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                    @else
                        <div class="w-12 h-12 rounded-full bg-indigo-800 flex items-center justify-center text-white font-extrabold text-base border-2 border-gray-300 dark:border-gray-600 shadow-md" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
                            {{ $headerInitials }}
                        </div>
                    @endif
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
                                @php
                                    // Handle array or object user
                                    if (is_array($message['user'])) {
                                        $userName = $message['user']['name'] ?? 'User';
                                        $userAvatarUrl = null;
                                    } else {
                                        $userName = $message['user']->name ?? 'User';
                                        $userAvatarUrl = $message['user']->getFilamentAvatarUrl();
                                    }
                                    $userInitials = strtoupper(substr($userName, 0, 1));
                                @endphp
                                @if($userAvatarUrl)
                                    <img src="{{ $userAvatarUrl }}" alt="{{ $userName }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0 border-2 border-gray-300 dark:border-gray-600">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-indigo-800 flex items-center justify-center text-white font-extrabold text-xs flex-shrink-0 border-2 border-gray-300 dark:border-gray-600 shadow-md" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
                                        {{ $userInitials }}
                                    </div>
                                @endif
                            @endif
                            
                            <div class="flex flex-col {{ $isCurrentUser ? 'items-end' : 'items-start' }}">
                                @php
                                    $isNegotiateRequest = false;
                                    $negotiateData = null;
                                    if (is_string($message['content']) && substr($message['content'], 0, 1) === '{') {
                                        $negotiateData = json_decode($message['content'], true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($negotiateData)) {
                                            $isNegotiateRequest = isset($negotiateData['type']) && $negotiateData['type'] === 'negotiate_request';
                                        }
                                    }
                                    
                                    // Fallback: check if content contains negotiate request pattern
                                    if (!$isNegotiateRequest && is_string($message['content']) && strpos($message['content'], '"type":"negotiate_request"') !== false) {
                                        $negotiateData = json_decode($message['content'], true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($negotiateData)) {
                                            $isNegotiateRequest = true;
                                        }
                                    }
                                @endphp

                                {{-- Debug: Content = {{ $message['content'] }}, Is Negotiate = {{ $isNegotiateRequest ? 'Yes' : 'No' }} --}}
                                
                                @if($isNegotiateRequest)
                                    {{-- Negotiate Request Card --}}
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border-2 border-gray-200 dark:border-gray-700 overflow-hidden max-w-md shadow-sm {{ $isCurrentUser ? 'bg-blue-600 border-blue-500' : '' }}">
                                        <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 {{ $isCurrentUser ? 'bg-blue-500/20 border-blue-400/30' : '' }}">
                                            <svg class="w-5 h-5 {{ $isCurrentUser ? 'text-white' : 'text-blue-600 dark:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            <span class="font-semibold text-sm {{ $isCurrentUser ? 'text-white' : 'text-gray-900 dark:text-white' }}">Price Negotiation</span>
                                        </div>
                                        
                                        <div class="p-4">
                                            <div class="space-y-2 mb-3">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-gray-600 dark:text-gray-400 {{ $isCurrentUser ? 'text-white/80' : '' }}">Original:</span>
                                                    <span class="text-base font-bold text-gray-500 line-through {{ $isCurrentUser ? 'text-white/60' : '' }}">€{{ number_format($negotiateData['original_price'], 2) }}</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-gray-600 dark:text-gray-400 {{ $isCurrentUser ? 'text-white/80' : '' }}">Proposed:</span>
                                                    <span class="text-lg font-bold {{ $isCurrentUser ? 'text-white' : 'text-green-600 dark:text-green-400' }}">€{{ number_format($negotiateData['proposed_price'], 2) }}</span>
                                                </div>
                                            </div>
                                            
                                            @if(isset($negotiateData['message']) && !empty($negotiateData['message']))
                                                <div class="p-3 rounded-lg mb-3 {{ $isCurrentUser ? 'bg-white/10' : 'bg-gray-50 dark:bg-gray-700' }}">
                                                    <p class="text-sm {{ $isCurrentUser ? 'text-white' : 'text-gray-700 dark:text-gray-300' }}">{{ $negotiateData['message'] }}</p>
                                                </div>
                                            @endif
                                            
                                            @if(isset($negotiateData['status']))
                                                @if($negotiateData['status'] === 'accepted')
                                                    <div class="flex items-center gap-2 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-3 py-2 rounded-lg text-sm font-semibold">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>Request Accepted</span>
                                                    </div>
                                                @elseif($negotiateData['status'] === 'declined')
                                                    <div class="flex items-center gap-2 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 px-3 py-2 rounded-lg text-sm font-semibold">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        <span>Request Declined</span>
                                                    </div>
                                                @elseif($negotiateData['status'] === 'counter_offered')
                                                    <div class="flex items-center gap-2 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 px-3 py-2 rounded-lg text-sm font-semibold">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                        </svg>
                                                        <span>Counter Offer: €{{ number_format($negotiateData['counter_price'], 2) }}</span>
                                                    </div>
                                                    @if(isset($negotiateData['counter_message']) && !empty($negotiateData['counter_message']))
                                                        <div class="mt-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded text-sm text-yellow-900 dark:text-yellow-200">
                                                            <p>{{ $negotiateData['counter_message'] }}</p>
                                                        </div>
                                                    @endif
                                                @endif
                                            @elseif(!$isCurrentUser)
                                                {{-- Show action buttons for seller --}}
                                                <div class="flex gap-2 mt-3">
                                                    <button 
                                                        wire:click="acceptNegotiateRequest({{ $message['id'] }})"
                                                        class="flex-1 flex items-center justify-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Accept
                                                    </button>
                                                    <button 
                                                        wire:click="declineNegotiateRequest({{ $message['id'] }})"
                                                        class="flex-1 flex items-center justify-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Decline
                                                    </button>
                                                    <button 
                                                        onclick="showCounterOfferModal({{ $message['id'] }})"
                                                        class="flex-1 flex items-center justify-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
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
                                    <div class="rounded-lg px-4 py-2 {{ $isCurrentUser ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                                        <p class="text-sm whitespace-pre-wrap break-words">{{ $message['content'] }}</p>
                                    </div>
                                @endif
                                
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($message['created_at'])->format('M d, H:i') }}
                                    @if($isCurrentUser && $message['is_read'])
                                        <span class="ml-1">✓✓</span>
                                    @endif
                                </span>
                            </div>
                            
                            @if($isCurrentUser)
                                @php
                                    $currentAvatarUrl = auth()->user()->getFilamentAvatarUrl();
                                    $currentUserName = auth()->user()->name;
                                    $currentInitials = strtoupper(substr($currentUserName, 0, 1));
                                @endphp
                                @if($currentAvatarUrl)
                                    <img src="{{ $currentAvatarUrl }}" alt="{{ $currentUserName }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0 border-2 border-gray-300 dark:border-gray-600">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-indigo-800 flex items-center justify-center text-white font-extrabold text-xs flex-shrink-0 border-2 border-gray-300 dark:border-gray-600 shadow-md" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
                                        {{ $currentInitials }}
                                    </div>
                                @endif
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
                @if($record->product->images && is_array($record->product->images) && count($record->product->images) > 0)
                    <img 
                        src="{{ Storage::url($record->product->first_image) }}" 
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
        class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50"
        style="display: none;"
        @click.self="show = false; price = ''; message = ''"
        @keydown.escape.window="show = false; price = ''; message = ''"
    >
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full"
                @click.stop
            >
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Make Counter Offer</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Counter Price (€)</label>
                            <input 
                                type="number" 
                                x-model="price"
                                step="0.01" 
                                min="0.01"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter your counter offer price">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message (optional)</label>
                            <textarea 
                                x-model="message"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Add a message to your counter offer"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button 
                            @click="show = false; price = ''; message = ''"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors">
                            Cancel
                        </button>
                        <button 
                            @click="if (price && price > 0) { @this.call('counterNegotiateRequest', messageId, parseFloat(price), message); show = false; price = ''; message = ''; } else { alert('Please enter a valid price'); }"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors">
                            Send Counter Offer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showCounterOfferModal(messageId) {
            window.dispatchEvent(new CustomEvent('counter-offer-modal', { detail: { messageId } }));
        }
    </script>
</x-filament-panels::page>