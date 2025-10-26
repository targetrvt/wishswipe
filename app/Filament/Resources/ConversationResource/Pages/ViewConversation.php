<?php

namespace App\Filament\Resources\ConversationResource\Pages;

use App\Filament\Resources\ConversationResource;
use App\Models\Message;
use App\Models\NegotiateRequest;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Livewire\Attributes\On;

class ViewConversation extends ViewRecord
{
    protected static string $resource = ConversationResource::class;
    
    protected static string $view = 'filament.resources.conversation-resource.pages.view-conversation';

    public $messageContent = '';
    public $messages = [];

    public function mount(int | string $record): void
    {
        parent::mount($record);
        $this->loadMessages();
        $this->markMessagesAsRead();
    }

    public function loadMessages()
    {
        $this->messages = $this->record->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }

    public function markMessagesAsRead()
    {
        $userId = auth()->id();
        
        $this->record->messages()
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function sendMessage()
    {
        if (empty(trim($this->messageContent))) {
            return;
        }

        Message::create([
            'conversation_id' => $this->record->id,
            'user_id' => auth()->id(),
            'content' => $this->messageContent,
            'is_read' => false,
        ]);

        $this->messageContent = '';
        $this->loadMessages();
        
        $this->dispatch('message-sent');
        $this->dispatch('scroll-to-bottom');
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

        $this->loadMessages();
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

        $this->loadMessages();
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

        $this->loadMessages();
    }

    #[On('refresh-messages')]
    public function refreshMessages()
    {
        $this->loadMessages();
        $this->markMessagesAsRead();
    }

    public function getOtherUser()
    {
        $userId = auth()->id();
        return $this->record->getOtherUser($userId);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->action(fn () => $this->refreshMessages()),
            
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}