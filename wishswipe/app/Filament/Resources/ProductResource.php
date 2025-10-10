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
    
    protected static ?string $navigationGroup = 'Marketplace';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Information')
                    ->description('Enter the basic information about your product')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., iPhone 13 Pro Max')
                            ->columnSpanFull()
                            ->live(onBlur: true),
                        
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(5)
                            ->placeholder('Provide a detailed description of your product...')
                            ->columnSpanFull()
                            ->helperText('Be specific about condition, features, and any defects'),
                        
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(fn () => Category::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0.01)
                            ->maxValue(999999.99)
                            ->step(0.01)
                            ->placeholder('0.00')
                            ->helperText('Set a competitive price'),
                        
                        Forms\Components\Select::make('condition')
                            ->options([
                                'new' => 'New',
                                'like_new' => 'Like New',
                                'used' => 'Used',
                            ])
                            ->required()
                            ->default('new'),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'available' => 'Available',
                                'reserved' => 'Reserved',
                                'sold' => 'Sold',
                            ])
                            ->required()
                            ->default('available'),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Forms\Components\Section::make('Location')
                    ->description('Help buyers find products near them')
                    ->schema([
                        Forms\Components\TextInput::make('location')
                            ->label('Location Address')
                            ->placeholder('e.g., Riga, Latvia or Brīvības iela 1, Rīga')
                            ->columnSpanFull()
                            ->helperText('Enter the full address where the product is located'),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step(0.0000001)
                                    ->default(56.9496)
                                    ->required()
                                    ->live()
                                    ->helperText('Will be used for nearby search'),
                                
                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step(0.0000001)
                                    ->default(24.1052)
                                    ->required()
                                    ->live()
                                    ->helperText('Will be used for nearby search'),
                            ]),
                        
                        // Map component - should now be visible since API key is configured
                        Map::make('computed_location')
                            ->label('Interactive Map (Drag marker to set location)')
                            ->mapControls([
                                'mapTypeControl' => true,
                                'scaleControl' => true,
                                'streetViewControl' => true,
                                'zoomControl' => true,
                            ])
                            ->height('400px')
                            ->defaultZoom(15)
                            ->draggable(true)
                            ->clickable(true)
                            ->columnSpanFull()
                            ->afterStateHydrated(function ($state, $record, $set, $get) {
                                if ($record && $record->latitude && $record->longitude) {
                                    $set('computed_location', [
                                        'lat' => (float) $record->latitude,
                                        'lng' => (float) $record->longitude,
                                    ]);
                                } else {
                                    $lat = $get('latitude') ?? 56.9496;
                                    $lng = $get('longitude') ?? 24.1052;
                                    $set('computed_location', [
                                        'lat' => (float) $lat,
                                        'lng' => (float) $lng,
                                    ]);
                                }
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (is_array($state) && isset($state['lat'], $state['lng'])) {
                                    $set('latitude', $state['lat']);
                                    $set('longitude', $state['lng']);
                                }
                            })
                            ->live()
                            ->dehydrated(false),
                            // Removed ->visible() to force it to show
                        
                        Forms\Components\Placeholder::make('map_link')
                            ->label('View on Map')
                            ->content(function ($record, $get) {
                                $lat = $get('latitude') ?? $record?->latitude ?? 56.9496;
                                $lng = $get('longitude') ?? $record?->longitude ?? 24.1052;
                                return new \Illuminate\Support\HtmlString(
                                    '<a href="https://www.google.com/maps/search/?api=1&query=' . $lat . ',' . $lng . '" 
                                        target="_blank" 
                                        class="text-primary-600 hover:text-primary-700 underline flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Open in Google Maps →
                                    </a>
                                    <p class="text-xs text-gray-500 mt-1">Click to verify the location on Google Maps</p>'
                                );
                            })
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->collapsible(),
                
                Forms\Components\Section::make('Product Images')
                    ->description('Upload up to 5 high-quality images')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
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
                            ->helperText('Recommended: Square images, at least 800x800px')
                            ->columnSpanFull()
                            ->reorderable()
                            ->appendFiles()
                            ->panelLayout('grid')
                            ->imagePreviewHeight('200'),
                    ])
                    ->collapsible(),
                
                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->label('Active Listing')
                            ->helperText('Inactive listings won\'t appear in search results')
                            ->inline(false),
                        
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => auth()->id()),
                        
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created')
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? '-')
                            ->hidden(fn ($record) => $record === null),
                        
                        Forms\Components\Placeholder::make('view_count')
                            ->label('Total Views')
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
                    ->label('Image')
                    ->circular()
                    ->stacked()
                    ->limit(1)
                    ->defaultImageUrl(url('/images/placeholder.jpg'))
                    ->getStateUsing(function ($record) {
                        if (!$record->images) {
                            return null;
                        }
                        $images = is_string($record->images) ? json_decode($record->images, true) : $record->images;
                        return is_array($images) && count($images) > 0 ? $images[0] : null;
                    }),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->weight('bold')
                    ->description(fn ($record) => $record->category->name ?? 'Uncategorized'),
                
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                
                Tables\Columns\BadgeColumn::make('condition')
                    ->colors([
                        'success' => 'new',
                        'info' => 'like_new',
                        'warning' => 'used',
                    ])
                    ->formatStateUsing(fn ($state) => $state ? ucfirst(str_replace('_', ' ', $state)) : 'N/A'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'available',
                        'warning' => 'reserved',
                        'danger' => 'sold',
                    ])
                    ->formatStateUsing(fn ($state) => $state ? ucfirst($state) : 'N/A'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->sortable()
                    ->alignCenter()
                    ->icon('heroicon-m-eye')
                    ->default(0),
                
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->limit(30)
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => $state ?: 'N/A'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->preload()
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('condition')
                    ->options([
                        'new' => 'New',
                        'like_new' => 'Like New',
                        'used' => 'Used',
                    ])
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'reserved' => 'Reserved',
                        'sold' => 'Sold',
                    ])
                    ->multiple(),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All products')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                
                Tables\Filters\Filter::make('has_images')
                    ->label('Has Images')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('images')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('view_map')
                        ->label('View on Map')
                        ->icon('heroicon-o-map-pin')
                        ->color('info')
                        ->visible(fn ($record) => $record->latitude && $record->longitude)
                        ->url(fn ($record) => "https://www.google.com/maps/search/?api=1&query={$record->latitude},{$record->longitude}")
                        ->openUrlInNewTab(),
                    
                    Tables\Actions\Action::make('mark_sold')
                        ->label('Mark as Sold')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status !== 'sold')
                        ->action(function ($record) {
                            $record->update(['status' => 'sold']);
                            Notification::make()
                                ->success()
                                ->title('Product marked as sold')
                                ->send();
                        }),
                    
                    Tables\Actions\Action::make('mark_available')
                        ->label('Mark as Available')
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status === 'sold')
                        ->action(function ($record) {
                            $record->update(['status' => 'available']);
                            Notification::make()
                                ->success()
                                ->title('Product marked as available')
                                ->send();
                        }),
                    
                    Tables\Actions\Action::make('toggle_active')
                        ->label(fn ($record) => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn ($record) => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                        ->color(fn ($record) => $record->is_active ? 'warning' : 'success')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['is_active' => !$record->is_active]);
                            Notification::make()
                                ->success()
                                ->title('Product updated')
                                ->send();
                        }),
                    
                    Tables\Actions\DeleteAction::make(),
                ])->icon('heroicon-m-ellipsis-vertical')
                ->button()
                ->label('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
                    
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create your first product')
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->striped();
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['category', 'user']);
        
        // Users can only see their own products, admins can see all
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