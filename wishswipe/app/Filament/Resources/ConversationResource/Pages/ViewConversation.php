<?php

namespace App\Filament\Resources\ConversationResource\Pages;

use App\Filament\Resources\ConversationResource;
use App\Models\Message;
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