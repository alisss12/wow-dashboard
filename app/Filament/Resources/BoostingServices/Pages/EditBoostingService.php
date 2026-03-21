<?php

namespace App\Filament\Resources\BoostingServices\Pages;

use App\Filament\Resources\BoostingServices\BoostingServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBoostingService extends EditRecord
{
    protected static string $resource = BoostingServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
