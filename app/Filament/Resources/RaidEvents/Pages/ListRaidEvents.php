<?php

namespace App\Filament\Resources\RaidEvents\Pages;

use App\Filament\Resources\RaidEvents\RaidEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRaidEvents extends ListRecords
{
    protected static string $resource = RaidEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
