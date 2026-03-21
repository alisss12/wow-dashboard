<?php

namespace App\Filament\Resources\RaidSignups\Pages;

use App\Filament\Resources\RaidSignups\RaidSignupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRaidSignups extends ListRecords
{
    protected static string $resource = RaidSignupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
