<?php

namespace App\Filament\Resources\RaidEvents\Pages;

use App\Filament\Resources\RaidEvents\RaidEventResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRaidEvent extends EditRecord
{
    protected static string $resource = RaidEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
