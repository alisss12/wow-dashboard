<?php

namespace App\Filament\Resources\BoosterApplications\Pages;

use App\Filament\Resources\BoosterApplications\BoosterApplicationResource;
use Filament\Resources\Pages\EditRecord;

class EditBoosterApplication extends EditRecord
{
    protected static string $resource = BoosterApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // When an application is approved, promote the user to 'booster'
        if ($this->record->status === 'approved') {
            $user = $this->record->user;
            if ($user && $user->account_type === 'user') {
                $user->update(['account_type' => 'booster']);
            }
        }
    }
}
