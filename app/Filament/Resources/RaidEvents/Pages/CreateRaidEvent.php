<?php

namespace App\Filament\Resources\RaidEvents\Pages;

use App\Filament\Resources\RaidEvents\RaidEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRaidEvent extends CreateRecord
{
    protected static string $resource = RaidEventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        // If the creator is a Booster, lock the run to their account automatically
        if ($user->account_type === 'booster') {
            $data['booster_user_id'] = $user->id;
        }

        return $data;
    }
}
