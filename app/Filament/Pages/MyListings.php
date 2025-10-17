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
    
    public static function getNavigationLabel(): string
    {
        return __('listings.navigation_label');
    }
    
    public function getTitle(): string
    {
        return __('listings.page_title');
    }
    
    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->where('user_id', auth()->id()))
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label(__('listings.columns.image'))
                    ->circular()
                    ->stacked()
                    ->limit(1)
                    ->getStateUsing(fn ($record) => $record->first_image),
                
                Tables\Columns\TextColumn::make('title')
                    ->label(__('listings.columns.title'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('listings.columns.category'))
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('price')
                    ->label(__('listings.columns.price'))
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('condition')
                    ->label(__('listings.columns.condition'))
                    ->colors([
                        'success' => 'new',
                        'warning' => 'like_new',
                        'danger' => 'used',
                    ])
                    ->formatStateUsing(fn ($state) => __("listings.condition.{$state}")),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('listings.columns.status'))
                    ->colors([
                        'success' => 'available',
                        'warning' => 'reserved',
                        'danger' => 'sold',
                    ])
                    ->formatStateUsing(fn ($state) => __("listings.status.{$state}")),
                
                Tables\Columns\TextColumn::make('view_count')
                    ->label(__('listings.columns.views'))
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('swipes_count')
                    ->counts('swipes')
                    ->label(__('listings.columns.total_swipes'))
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('likes_count')
                    ->label(__('listings.columns.likes'))
                    ->getStateUsing(fn ($record) => $record->swipes()->where('direction', 'right')->count())
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('listings.columns.active')),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('listings.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('listings.filters.status'))
                    ->options([
                        'available' => __('listings.status.available'),
                        'reserved' => __('listings.status.reserved'),
                        'sold' => __('listings.status.sold'),
                    ]),
                
                Tables\Filters\SelectFilter::make('category')
                    ->label(__('listings.filters.category'))
                    ->relationship('category', 'name'),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('listings.filters.active')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('view')
                        ->label(__('listings.actions.view'))
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->modalHeading(fn ($record) => $record->title)
                        ->modalContent(fn ($record) => view('filament.pages.view-product-modal', ['record' => $record]))
                        ->modalWidth('5xl')
                        ->slideOver(),
                    
                    Tables\Actions\Action::make('view_map')
                        ->label(__('listings.actions.view_map'))
                        ->icon('heroicon-o-map-pin')
                        ->color('info')
                        ->visible(fn ($record) => $record->latitude && $record->longitude)
                        ->url(fn ($record) => "https://www.google.com/maps/search/?api=1&query={$record->latitude},{$record->longitude}")
                        ->openUrlInNewTab(),
                    
                    Tables\Actions\EditAction::make()
                        ->label(__('listings.actions.edit'))
                        ->form([
                            Forms\Components\TextInput::make('title')
                                ->label(__('my_listings.form.title'))
                                ->required()
                                ->maxLength(255),
                            
                            Forms\Components\Textarea::make('description')
                                ->label(__('my_listings.form.description'))
                                ->required()
                                ->rows(4),
                            
                            Forms\Components\Select::make('category_id')
                                ->label(__('my_listings.form.category'))
                                ->options(fn () => Category::where('is_active', true)->pluck('name', 'id'))
                                ->required()
                                ->searchable(),
                            
                            Forms\Components\TextInput::make('price')
                                ->label(__('my_listings.form.price'))
                                ->required()
                                ->numeric()
                                ->prefix('€'),
                            
                            Forms\Components\Select::make('condition')
                                ->label(__('my_listings.form.condition'))
                                ->options([
                                    'new' => __('my_listings.condition.new'),
                                    'like_new' => __('my_listings.condition.like_new'),
                                    'used' => __('my_listings.condition.used'),
                                ])
                                ->required(),
                            
                            Forms\Components\Select::make('status')
                                ->label(__('my_listings.form.status'))
                                ->options([
                                    'available' => __('my_listings.status.available'),
                                    'reserved' => __('my_listings.status.reserved'),
                                    'sold' => __('my_listings.status.sold'),
                                ])
                                ->required(),
                            
                            Geocomplete::make('location_address')
                                ->label(__('my_listings.form.location'))
                                ->isLocation()
                                ->default([])
                                ->reverseGeocode([
                                    'city' => '%L',
                                    'zip' => '%z',
                                    'state' => '%A1',
                                    'country' => '%c',
                                ])
                                ->countries(['us', 'gb', 'lv'])
                                ->placeholder(__('my_listings.form.location_placeholder'))
                                ->columnSpanFull(),
                            
                            Map::make('location')
                                ->label(__('my_listings.form.map'))
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
                                ->label(__('my_listings.form.images'))
                                ->multiple()
                                ->image()
                                ->maxFiles(5)
                                ->directory('product-images'),
                            
                            Forms\Components\Toggle::make('is_active')
                                ->label(__('my_listings.form.active')),
                        ])
                        ->modalWidth('5xl'),
                    
                    Tables\Actions\Action::make('mark_sold')
                        ->label(__('my_listings.actions.mark_sold'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status !== 'sold')
                        ->action(fn ($record) => $record->update(['status' => 'sold'])),
                    
                    Tables\Actions\Action::make('mark_available')
                        ->label(__('my_listings.actions.mark_available'))
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status === 'sold')
                        ->action(fn ($record) => $record->update(['status' => 'available'])),
                    
                    Tables\Actions\DeleteAction::make()
                        ->label(__('my_listings.actions.delete')),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('my_listings.bulk_actions.delete')),
                    
                    Tables\Actions\BulkAction::make('mark_available')
                        ->label(__('my_listings.bulk_actions.mark_available'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'available'])),
                    
                    Tables\Actions\BulkAction::make('mark_sold')
                        ->label(__('my_listings.bulk_actions.mark_sold'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'sold'])),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label(__('my_listings.empty_state.create_first'))
                    ->url(fn (): string => route('filament.app.resources.products.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }
}