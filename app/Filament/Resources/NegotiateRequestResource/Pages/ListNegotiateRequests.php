<?php

namespace App\Filament\Resources\NegotiateRequestResource\Pages;

use App\Filament\Resources\NegotiateRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNegotiateRequests extends ListRecords
{
    protected static string $resource = NegotiateRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
