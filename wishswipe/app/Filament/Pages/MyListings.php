<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Product;
use App\Models\Category;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Cheesegrits\FilamentGoogleMaps\Fields\Geocomplete;

class MyListings extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static string $view = 'filament.pages.my-listings';
    
    protected static ?string $title = 'My Listings';
    
    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->where('user_id', auth()->id()))
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Image')
                    ->circular()
                    ->stacked()
                    ->limit(1)
                    ->getStateUsing(fn ($record) => $record->images[0] ?? null),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('condition')
                    ->colors([
                        'success' => 'new',
                        'warning' => 'like_new',
                        'danger' => 'used',
                    ]),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'available',
                        'warning' => 'reserved',
                        'danger' => 'sold',
                    ]),
                
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('swipes_count')
                    ->counts('swipes')
                    ->label('Total Swipes')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('likes_count')
                    ->label('Likes')
                    ->getStateUsing(fn ($record) => $record->swipes()->where('direction', 'right')->count())
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'reserved' => 'Reserved',
                        'sold' => 'Sold',
                    ]),
                
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->modalHeading(fn ($record) => $record->title)
                        ->modalContent(fn ($record) => view('filament.pages.view-product-modal', ['record' => $record]))
                        ->modalWidth('5xl')
                        ->slideOver(),
                    
                    Tables\Actions\Action::make('view_map')
                        ->label('View on Map')
                        ->icon('heroicon-o-map-pin')
                        ->color('info')
                        ->visible(fn ($record) => $record->latitude && $record->longitude)
                        ->url(fn ($record) => "https://www.google.com/maps/search/?api=1&query={$record->latitude},{$record->longitude}")
                        ->openUrlInNewTab(),
                    
                    Tables\Actions\EditAction::make()
                        ->form([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->maxLength(255),
                            
                            Forms\Components\Textarea::make('description')
                                ->required()
                                ->rows(4),
                            
                            Forms\Components\Select::make('category_id')
                                ->label('Category')
                                ->options(fn () => Category::where('is_active', true)->pluck('name', 'id'))
                                ->required()
                                ->searchable(),
                            
                            Forms\Components\TextInput::make('price')
                                ->required()
                                ->numeric()
                                ->prefix('$'),
                            
                            Forms\Components\Select::make('condition')
                                ->options([
                                    'new' => 'New',
                                    'like_new' => 'Like New',
                                    'used' => 'Used',
                                ])
                                ->required(),
                            
                            Forms\Components\Select::make('status')
                                ->options([
                                    'available' => 'Available',
                                    'reserved' => 'Reserved',
                                    'sold' => 'Sold',
                                ])
                                ->required(),
                            
                            Geocomplete::make('location')
                                ->isLocation()
                                ->reverseGeocode([
                                    'city' => '%L',
                                    'zip' => '%z',
                                    'state' => '%A1',
                                    'country' => '%c',
                                ])
                                ->countries(['us', 'gb', 'lv'])
                                ->placeholder('Search for a location...')
                                ->columnSpanFull(),
                            
                            Map::make('location')
                                ->mapControls([
                                    'mapTypeControl' => true,
                                    'scaleControl' => true,
                                    'streetViewControl' => true,
                                    'rotateControl' => true,
                                    'fullscreenControl' => true,
                                    'searchBoxControl' => false,
                                    'zoomControl' => true,
                                ])
                                ->height(fn () => '300px')
                                ->defaultZoom(15)
                                ->autocomplete('location')
                                ->autocompleteReverse(true)
                                ->defaultLocation([56.9496, 24.1052])
                                ->draggable()
                                ->clickable(true)
                                ->columnSpanFull(),
                            
                            Forms\Components\FileUpload::make('images')
                                ->multiple()
                                ->image()
                                ->maxFiles(5)
                                ->directory('product-images'),
                            
                            Forms\Components\Toggle::make('is_active')
                                ->label('Active'),
                        ])
                        ->modalWidth('5xl'),
                    
                    Tables\Actions\Action::make('mark_sold')
                        ->label('Mark as Sold')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status !== 'sold')
                        ->action(fn ($record) => $record->update(['status' => 'sold'])),
                    
                    Tables\Actions\Action::make('mark_available')
                        ->label('Mark as Available')
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status === 'sold')
                        ->action(fn ($record) => $record->update(['status' => 'available'])),
                    
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('mark_available')
                        ->label('Mark as Available')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'available'])),
                    
                    Tables\Actions\BulkAction::make('mark_sold')
                        ->label('Mark as Sold')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'sold'])),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Create your first listing')
                    ->url(fn (): string => route('filament.app.resources.products.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }
}