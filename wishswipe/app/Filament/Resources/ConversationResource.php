<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConversationResource\Pages;
use App\Models\Conversation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ConversationResource extends Resource
{
    protected static ?string $model = Conversation::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationGroup = 'Management';
    
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Conversation Details')
                    ->schema([
                        Forms\Components\Select::make('matched_id')
                            ->label('Match')
                            ->relationship('matched', 'id')
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                        
                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'title')
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                        
                        Forms\Components\DateTimePicker::make('last_message_at')
                            ->label('Last Message')
                            ->disabled(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Participants')
                    ->schema([
                        Forms\Components\Placeholder::make('buyer')
                            ->label('Buyer')
                            ->content(fn ($record) => $record?->matched?->buyer?->name ?? 'N/A'),
                        
                        Forms\Components\Placeholder::make('seller')
                            ->label('Seller')
                            ->content(fn ($record) => $record?->matched?->seller?->name ?? 'N/A'),
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
                
                Tables\Columns\TextColumn::make('matched.buyer.name')
                    ->label('Buyer')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('matched.seller.name')
                    ->label('Seller')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('product.title')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('messages_count')
                    ->counts('messages')
                    ->label('Messages')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('last_message_at')
                    ->label('Last Activity')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->placeholder('No messages yet'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_messages')
                    ->label('Has Messages')
                    ->query(fn (Builder $query): Builder => $query->has('messages')),
                
                Tables\Filters\Filter::make('no_messages')
                    ->label('No Messages')
                    ->query(fn (Builder $query): Builder => $query->doesntHave('messages')),
                
                Tables\Filters\Filter::make('recent')
                    ->label('Recent (Last 7 days)')
                    ->query(fn (Builder $query): Builder => $query->where('last_message_at', '>=', now()->subDays(7))),
            ])
            ->actions([
                Tables\Actions\Action::make('view_chat')
                    ->label('View Chat')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.app.resources.conversations.view', $record)),
                
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('last_message_at', 'desc');
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
            'index' => Pages\ListConversations::route('/'),
            'create' => Pages\CreateConversation::route('/create'),
            'view' => Pages\ViewConversation::route('/{record}'),
            'edit' => Pages\EditConversation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->id();
        
        return parent::getEloquentQuery()
            ->whereHas('matched', function ($query) use ($userId) {
                $query->where('buyer_id', $userId)
                    ->orWhere('seller_id', $userId);
            })
            ->with(['matched.buyer', 'matched.seller', 'product', 'latestMessage']);
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