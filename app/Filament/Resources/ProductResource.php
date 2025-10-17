<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Cheesegrits\FilamentGoogleMaps\Fields\Geocomplete;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function getNavigationLabel(): string
    {
        return __('listings.navigation_label');
    }
     
    public static function getPluralModelLabel(): string
    {
        return __('listings.navigation_label');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.marketplace');
    }
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('listings.filament.product_information'))
                    ->description(__('listings.filament.product_information_description'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('listings.form.title'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('listings.filament.title_placeholder'))
                            ->columnSpanFull()
                            ->live(onBlur: true),
                        
                        Forms\Components\Textarea::make('description')
                            ->label(__('listings.form.description'))
                            ->required()
                            ->rows(5)
                            ->placeholder(__('listings.filament.description_placeholder'))
                            ->columnSpanFull()
                            ->helperText(__('listings.filament.description_helper')),
                        
                        Forms\Components\Select::make('category_id')
                            ->label(__('listings.form.category'))
                            ->options(fn () => Category::where('is_active', true)->pluck('name', 'id'))
                            ->required(),
                        
                        Forms\Components\TextInput::make('price')
                            ->label(__('listings.form.price'))
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->minValue(0.01)
                            ->maxValue(999999.99)
                            ->step(0.01)
                            ->placeholder(__('listings.filament.price_placeholder'))
                            ->helperText(__('listings.filament.price_helper')),
                        
                        Forms\Components\Select::make('condition')
                            ->label(__('listings.form.condition'))
                            ->options(__('listings.filament.condition_options'))
                            ->required()
                            ->default('new'),
                        
                        Forms\Components\Select::make('status')
                            ->label(__('listings.form.status'))
                            ->options(__('listings.filament.status_options'))
                            ->required()
                            ->default('available'),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Forms\Components\Section::make(__('listings.form.location'))
                    ->description(__('listings.filament.location_helper'))
                    ->schema([
                        Geocomplete::make('location')
                            ->label('Location Description')
                            ->isLocation()
                            ->default([])
                            ->reverseGeocode([
                                'city' => '%L',
                                'zip' => '%z',
                                'state' => '%A1',
                                'country' => '%c',
                            ])
                            ->countries(['us', 'gb', 'lv'])
                            ->placeholder('e.g., Riga, Latvia'),
                        Forms\Components\TextInput::make('location')
                            ->label(__('listings.form.location'))
                            ->maxLength(255)
                            ->placeholder(__('listings.filament.location_placeholder'))
                            ->columnSpanFull(),
                        
                        Map::make('coordinates')
                            ->label(__('listings.filament.map_label'))
                            ->mapControls([
                                'mapTypeControl' => true,
                                'scaleControl' => true,
                                'streetViewControl' => true,
                                'rotateControl' => true,
                                'fullscreenControl' => true,
                                'searchBoxControl' => false,
                                'zoomControl' => true,
                            ])
                            ->height(fn () => '450px')
                            ->defaultZoom(13)
                            ->autocomplete('location')
                            ->autocompleteReverse(true)
                            ->reverseGeocode([
                                'location' => '%n %S, %L, %A1 %z',
                            ])
                            ->defaultLocation([56.9496, 24.1052])
                            ->draggable()
                            ->clickable()
                            ->geolocate()
                            ->geolocateLabel(__('listings.filament.geolocate_label'))
                            ->geolocateOnLoad(false, false)
                            ->columnSpanFull()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                if (is_array($state) && isset($state['lat']) && isset($state['lng'])) {
                                    $lat = round((float) $state['lat'], 4);
                                    $lng = round((float) $state['lng'], 4);
                                    $set('latitude', $lat);
                                    $set('longitude', $lng);
                                }
                            })
                            ->helperText(__('listings.filament.map_helper')),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label(__('listings.filament.latitude_label'))
                                    ->numeric()
                                    ->step(0.0001)
                                    ->minValue(-90)
                                    ->maxValue(90)
                                    ->rule('regex:/^-?\\d{1,2}\\.\\d{1,4}$/')
                                    ->placeholder(__('listings.filament.latitude_placeholder'))
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $rounded = is_numeric($state) ? round((float) $state, 4) : null;
                                        if ($rounded !== null) {
                                            $set('latitude', $rounded);
                                        }
                                        $lng = $get('longitude');
                                        if ($rounded !== null && $lng) {
                                            $set('coordinates', [
                                                'lat' => (float) $rounded,
                                                'lng' => (float) round((float) $lng, 4),
                                            ]);
                                        }
                                    })
                                    ->helperText(__('listings.filament.coordinates_helper')),
                                
                                Forms\Components\TextInput::make('longitude')
                                    ->label(__('listings.filament.longitude_label'))
                                    ->numeric()
                                    ->step(0.0001)
                                    ->minValue(-180)
                                    ->maxValue(180)
                                    ->rule('regex:/^-?\\d{1,3}\\.\\d{1,4}$/')
                                    ->placeholder(__('listings.filament.longitude_placeholder'))
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $rounded = is_numeric($state) ? round((float) $state, 4) : null;
                                        if ($rounded !== null) {
                                            $set('longitude', $rounded);
                                        }
                                        $lat = $get('latitude');
                                        if ($rounded !== null && $lat) {
                                            $set('coordinates', [
                                                'lat' => (float) round((float) $lat, 4),
                                                'lng' => (float) $rounded,
                                            ]);
                                        }
                                    })
                                    ->helperText(__('listings.filament.coordinates_helper')),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(false),
                
                Forms\Components\Section::make(__('listings.filament.images_section'))
                    ->description(__('listings.filament.images_description'))
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label(__('listings.form.images'))
                            ->multiple()
                            ->image()
                            ->maxFiles(5)
                            ->directory('product-images')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '4:3',
                                '16:9',
                            ])
                            ->maxSize(5120)
                            ->helperText(__('listings.filament.images_helper'))
                            ->columnSpanFull()
                            ->reorderable()
                            ->appendFiles()
                            ->panelLayout('grid')
                            ->imagePreviewHeight('200'),
                    ])
                    ->collapsible(),
                
                Forms\Components\Section::make(__('listings.filament.visibility_section'))
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->label(__('listings.form.active'))
                            ->helperText(__('listings.filament.active_helper'))
                            ->inline(false),
                        
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => auth()->id()),
                        
                        Forms\Components\Placeholder::make('created_at')
                            ->label(__('listings.filament.created_label'))
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? '-')
                            ->hidden(fn ($record) => $record === null),
                        
                        Forms\Components\Placeholder::make('view_count')
                            ->label(__('listings.filament.total_views_label'))
                            ->content(fn ($record) => number_format($record?->view_count ?? 0))
                            ->hidden(fn ($record) => $record === null),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label(__('listings.columns.image'))
                    ->circular()
                    ->stacked()
                    ->limit(1)
                    ->defaultImageUrl(url('/images/placeholder.jpg'))
                    ->getStateUsing(fn ($record) => $record->first_image),
                
                Tables\Columns\TextColumn::make('title')
                    ->label(__('listings.columns.title'))
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->weight('bold')
                    ->description(fn ($record) => $record->category->name ?? __('listings.modal.uncategorized')),
                
                Tables\Columns\TextColumn::make('price')
                    ->label(__('listings.columns.price'))
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                
                Tables\Columns\BadgeColumn::make('condition')
                    ->label(__('listings.columns.condition'))
                    ->colors([
                        'success' => 'new',
                        'info' => 'like_new',
                        'warning' => 'used',
                    ])
                    ->formatStateUsing(fn ($state) => __('listings.condition.' . $state)),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('listings.columns.status'))
                    ->colors([
                        'success' => 'available',
                        'warning' => 'reserved',
                        'danger' => 'sold',
                    ])
                    ->formatStateUsing(fn ($state) => __('listings.status.' . $state)),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('listings.columns.active'))
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('view_count')
                    ->label(__('listings.columns.views'))
                    ->sortable()
                    ->alignCenter()
                    ->icon('heroicon-m-eye'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('listings.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label(__('listings.filters.category'))
                    ->relationship('category', 'name')
                    ->preload()
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('condition')
                    ->label(__('listings.form.condition'))
                    ->options(__('listings.filament.condition_options'))
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('listings.filters.status'))
                    ->options(__('listings.filament.status_options'))
                    ->multiple(),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('listings.filament.active_label'))
                    ->placeholder(__('listings.all_listings'))
                    ->trueLabel(__('listings.status.available'))
                    ->falseLabel(__('listings.status.sold')),
                
                Tables\Filters\Filter::make('has_images')
                    ->label(__('listings.filament.has_images_label'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('images')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label(__('listings.actions.view')),
                    Tables\Actions\EditAction::make()
                        ->label(__('listings.actions.edit')),
                    
                    Tables\Actions\Action::make('mark_sold')
                        ->label(__('listings.filament.mark_sold_label'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status !== 'sold')
                        ->action(function ($record) {
                            $record->update(['status' => 'sold']);
                            Notification::make()
                                ->success()
                                ->title(__('listings.filament.product_marked_sold'))
                                ->send();
                        }),
                    
                    Tables\Actions\Action::make('mark_available')
                        ->label(__('listings.filament.mark_available_label'))
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status === 'sold')
                        ->action(function ($record) {
                            $record->update(['status' => 'available']);
                            Notification::make()
                                ->success()
                                ->title(__('listings.filament.product_marked_available'))
                                ->send();
                        }),
                    
                    Tables\Actions\Action::make('toggle_active')
                        ->label(fn ($record) => $record->is_active ? __('listings.filament.deactivate_label') : __('listings.filament.activate_label'))
                        ->icon(fn ($record) => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                        ->color(fn ($record) => $record->is_active ? 'warning' : 'success')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['is_active' => !$record->is_active]);
                            Notification::make()
                                ->success()
                                ->title(__('listings.filament.product_updated'))
                                ->send();
                        }),
                    
                    Tables\Actions\DeleteAction::make()
                        ->label(__('listings.actions.delete')),
                ])->icon('heroicon-m-ellipsis-vertical')
                ->button()
                ->label(__('listings.filament.actions_label')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_available')
                        ->label(__('listings.filament.bulk_mark_available_label'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'available'])),
                    
                    Tables\Actions\BulkAction::make('mark_sold')
                        ->label(__('listings.filament.bulk_mark_sold_label'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'sold'])),
                    
                    Tables\Actions\BulkAction::make('activate')
                        ->label(__('listings.filament.bulk_activate_label'))
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label(__('listings.filament.bulk_deactivate_label'))
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                    
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('listings.actions.delete')),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('listings.filament.create_first_product'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->striped();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['category', 'user']);
        
        if (!auth()->user()->hasRole('super_admin')) {
            $query->where('user_id', auth()->id());
        }
        
        return $query;
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('user_id', auth()->id())->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}