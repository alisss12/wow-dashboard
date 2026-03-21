<?php

namespace App\Filament\Resources\BoosterApplications\Pages;

use App\Filament\Resources\BoosterApplications\BoosterApplicationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBoosterApplications extends ListRecords
{
    protected static string $resource = BoosterApplicationResource::class;
 
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
