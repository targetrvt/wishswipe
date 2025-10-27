<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getSavedNotificationTitle(): ?string
    {
        return __('listings.filament.product_updated');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label(__('listings.actions.delete'))
                ->modalHeading(__('listings.filament.delete_confirm_heading'))
                ->modalDescription(__('listings.filament.delete_confirm_description'))
                ->modalSubmitActionLabel(__('listings.filament.confirm'))
                ->modalCancelActionLabel(__('listings.filament.cancel')),
        ];
    }
}
