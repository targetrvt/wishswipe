<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\NegotiateRequest;
use Livewire\Attributes\Computed;

class ConversationsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static string $view = 'filament.pages.conversations-page';
    
    public static function getNavigationLabel(): string
    {
        return __('conversations.navigation_label');
    }
    
    public function getTitle(): string
    {
        return __('conversations.page_title');
    }
    
    protected static ?int $navigationSort = 3;

    public $selectedConversationId = null;
    public $messageContent = '';

    #[Computed]
    public function conversations()
    {
        $userId = auth()->id();
        
        return Conversation::whereHas('matched', function ($query) use ($userId) {
            $query->where('buyer_id', $userId)
                ->orWhere('seller_id', $userId);
        })
        ->with(['matched.buyer', 'matched.seller', 'product.category', 'product.user', 'latestMessage'])
        ->orderByDesc('created_at')
        ->orderByDesc('last_message_at')
        ->get()
        ->map(function ($conversation) use ($userId) {
            $otherUser = $conversation->getOtherUser($userId);
            $hasUnread = $conversation->hasUnreadMessages($userId);
            
            return [
                'id' => $conversation->id,
                'product' => $conversation->product,
                'other_user' => $otherUser,
                'last_message' => $conversation->latestMessage,
                'has_unread' => $hasUnread,
                'last_message_at' => $conversation->last_message_at,
            ];
        });
    }

    #[Computed]
    public function selectedConversation()
    {
        if (!$this->selectedConversationId) {
            return null;
        }

        $conversation = Conversation::with([
            'matched.buyer', 
            'matched.seller', 
            'product.category', 
            'product.user',
            'messages.user'
        ])
            ->find($this->selectedConversationId);

        if (!$conversation) {
            return null;
        }

        // Mark messages as read
        $conversation->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $conversation;
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
        $this->dispatch('conversation-selected');
    }

    public function sendMessage()
    {
        if (empty(trim($this->messageContent)) || !$this->selectedConversationId) {
            return;
        }

        Message::create([
            'conversation_id' => $this->selectedConversationId,
            'user_id' => auth()->id(),
            'content' => $this->messageContent,
            'is_read' => false,
        ]);

        $this->messageContent = '';
        $this->dispatch('message-sent');
    }

    public function acceptNegotiateRequest($messageId)
    {
        $message = Message::find($messageId);
        if (!$message) return;

        $content = json_decode($message->content, true);
        if ($content['type'] !== 'negotiate_request') return;

        // Create a negotiate request record
        $negotiateRequest = NegotiateRequest::create([
            'buyer_id' => $message->user_id,
            'seller_id' => auth()->id(),
            'product_id' => $content['product_id'],
            'proposed_price' => $content['proposed_price'],
            'message' => $content['message'] ?? 'I would like to negotiate the price for this item.',
            'expires_at' => now()->addDays(7),
        ]);

        // Update the message to show it was accepted
        $content['status'] = 'accepted';
        $content['negotiate_request_id'] = $negotiateRequest->id;
        $message->update(['content' => json_encode($content)]);

        $this->dispatch('message-sent');
    }

    public function declineNegotiateRequest($messageId)
    {
        $message = Message::find($messageId);
        if (!$message) return;

        $content = json_decode($message->content, true);
        if ($content['type'] !== 'negotiate_request') return;

        // Update the message to show it was declined
        $content['status'] = 'declined';
        $message->update(['content' => json_encode($content)]);

        $this->dispatch('message-sent');
    }

    public function counterNegotiateRequest($messageId, $counterPrice, $counterMessage = null)
    {
        $message = Message::find($messageId);
        if (!$message) return;

        $content = json_decode($message->content, true);
        if ($content['type'] !== 'negotiate_request') return;

        // Create a negotiate request record
        $negotiateRequest = NegotiateRequest::create([
            'buyer_id' => $message->user_id,
            'seller_id' => auth()->id(),
            'product_id' => $content['product_id'],
            'proposed_price' => $counterPrice,
            'message' => $counterMessage,
            'expires_at' => now()->addDays(7),
        ]);

        // Update the message to show it was counter offered
        $content['status'] = 'counter_offered';
        $content['negotiate_request_id'] = $negotiateRequest->id;
        $content['counter_price'] = $counterPrice;
        $content['counter_message'] = $counterMessage;
        $message->update(['content' => json_encode($content)]);

        $this->dispatch('message-sent');
    }

    public static function getNavigationBadge(): ?string
    {
        $userId = auth()->id();
        
        $unreadCount = Conversation::whereHas('matched', function ($query) use ($userId) {
            $query->where('buyer_id', $userId)
                ->orWhere('seller_id', $userId);
        })
        ->whereHas('messages', function ($query) use ($userId) {
            $query->where('user_id', '!=', $userId)
                ->where('is_read', false);
        })
        ->count();
        
        return $unreadCount > 0 ? (string) $unreadCount : null;
    }
}