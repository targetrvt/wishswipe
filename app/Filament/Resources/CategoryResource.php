<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.management');
    }
    
    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Category Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state)))
                            ->placeholder('e.g., Electronics')
                            ->helperText('The display name of the category'),
                        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g., electronics')
                            ->helperText('Auto-generated from name, used in URLs'),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Brief description of this category...')
                            ->helperText('Optional: Help users understand what belongs in this category'),
                        
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon (Emoji or Class)')
                            ->placeholder('📱 or fas fa-mobile-alt')
                            ->helperText('Use an emoji or Font Awesome icon class')
                            ->maxLength(100),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->label('Active')
                            ->helperText('Inactive categories won\'t appear in product listings')
                            ->inline(false),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Statistics')
                    ->schema([
                        Forms\Components\Placeholder::make('products_count')
                            ->label('Total Products')
                            ->content(fn ($record) => $record?->products()->count() ?? 0),
                        
                        Forms\Components\Placeholder::make('active_products')
                            ->label('Active Products')
                            ->content(fn ($record) => $record?->products()->where('status', 'available')->count() ?? 0),
                        
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created')
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? '-'),
                    ])
                    ->columns(3)
                    ->hidden(fn ($record) => $record === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label('')
                    ->formatStateUsing(fn ($state) => $state ?: '📦')
                    ->size('lg'),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->description),
                
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug copied')
                    ->copyMessageDuration(1500)
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('active_products')
                    ->label('Active')
                    ->getStateUsing(fn ($record) => $record->products()->where('status', 'available')->count())
                    ->alignCenter()
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All categories')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                
                Tables\Filters\Filter::make('has_products')
                    ->label('Has Products')
                    ->query(fn ($query) => $query->has('products')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('toggle_active')
                        ->label(fn ($record) => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn ($record) => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                        ->color(fn ($record) => $record->is_active ? 'warning' : 'success')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['is_active' => !$record->is_active]);
                            Notification::make()
                                ->success()
                                ->title('Category updated')
                                ->body($record->is_active ? 'Category activated' : 'Category deactivated')
                                ->send();
                        }),
                    
                    Tables\Actions\DeleteAction::make()
                        ->before(function ($record, $action) {
                            if ($record->products()->count() > 0) {
                                Notification::make()
                                    ->danger()
                                    ->title('Cannot delete category')
                                    ->body('This category has products. Please reassign or delete them first.')
                                    ->send();
                                $action->cancel();
                            }
                        }),
                ])->icon('heroicon-m-ellipsis-vertical')
                ->button()
                ->label('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['is_active' => true]);
                            Notification::make()
                                ->success()
                                ->title('Categories activated')
                                ->send();
                        }),
                    
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['is_active' => false]);
                            Notification::make()
                                ->success()
                                ->title('Categories deactivated')
                                ->send();
                        }),
                    
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records, $action) {
                            $hasProducts = $records->filter(fn ($record) => $record->products()->count() > 0);
                            if ($hasProducts->count() > 0) {
                                Notification::make()
                                    ->danger()
                                    ->title('Cannot delete categories')
                                    ->body('Some categories have products. Please reassign or delete them first.')
                                    ->send();
                                $action->cancel();
                            }
                        }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create first category')
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->defaultSort('name')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }
}