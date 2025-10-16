<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Conversation;
use Livewire\Attributes\Computed;

class ConversationsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static string $view = 'filament.pages.conversations-page';
    
    protected static ?string $title = 'My Conversations';
    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $navigationLabel = 'Messages';

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
        ->with(['matched.buyer', 'matched.seller', 'product', 'latestMessage'])
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

        $conversation = Conversation::with(['matched.buyer', 'matched.seller', 'product', 'messages.user'])
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

        \App\Models\Message::create([
            'conversation_id' => $this->selectedConversationId,
            'user_id' => auth()->id(),
            'content' => $this->messageContent,
            'is_read' => false,
        ]);

        $this->messageContent = '';
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