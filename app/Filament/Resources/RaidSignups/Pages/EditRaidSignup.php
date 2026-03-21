<?php

namespace App\Filament\Resources\RaidSignups\Pages;

use App\Filament\Resources\RaidSignups\RaidSignupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRaidSignup extends EditRecord
{
    protected static string $resource = RaidSignupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
