<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.management');
    }
    
    protected static ?int $navigationSort = 6;
    
    protected static bool $shouldRegisterNavigation = false; // Hide from navigation

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Message Details')
                    ->schema([
                        Forms\Components\Select::make('conversation_id')
                            ->label('Conversation')
                            ->relationship('conversation', 'id')
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                        
                        Forms\Components\Select::make('user_id')
                            ->label('Sender')
                            ->relationship('user', 'name')
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                        
                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        Forms\Components\Toggle::make('is_read')
                            ->label('Read')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('conversation_id')
                    ->label('Conversation')
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Sender')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('content')
                    ->label('Message')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),
                
                Tables\Columns\IconColumn::make('is_read')
                    ->boolean()
                    ->label('Read')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('conversation.product.title')
                    ->label('Product')
                    ->limit(30)
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sent At')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Read Status')
                    ->placeholder('All messages')
                    ->trueLabel('Read')
                    ->falseLabel('Unread'),
                
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Sender'),
                
                Tables\Filters\Filter::make('my_messages')
                    ->label('My Messages')
                    ->query(fn (Builder $query): Builder => $query->where('user_id', auth()->id())),
                
                Tables\Filters\Filter::make('received_messages')
                    ->label('Received Messages')
                    ->query(fn (Builder $query): Builder => $query->where('user_id', '!=', auth()->id())),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_read')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_read && $record->user_id !== auth()->id())
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->markAsRead()),
                
                Tables\Actions\Action::make('view_conversation')
                    ->label('View Conversation')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.app.resources.conversations.view', $record->conversation_id)),
                
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_as_read')
                        ->label('Mark as Read')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->markAsRead()),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->id();
        
        return parent::getEloquentQuery()
            ->whereHas('conversation.matched', function ($query) use ($userId) {
                $query->where('buyer_id', $userId)
                    ->orWhere('seller_id', $userId);
            })
            ->with(['user', 'conversation.product']);
    }

    public static function getNavigationBadge(): ?string
    {
        $userId = auth()->id();
        
        $unreadCount = Message::whereHas('conversation.matched', function ($query) use ($userId) {
            $query->where('buyer_id', $userId)
                ->orWhere('seller_id', $userId);
        })
        ->where('user_id', '!=', $userId)
        ->where('is_read', false)
        ->count();
        
        return $unreadCount > 0 ? (string) $unreadCount : null;
    }
}