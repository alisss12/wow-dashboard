<?php

namespace App\Filament\Resources\BoostingServices\Pages;

use App\Filament\Resources\BoostingServices\BoostingServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBoostingServices extends ListRecords
{
    protected static string $resource = BoostingServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
