<?php

namespace App\Filament\Resources\VoteSites\Pages;

use App\Filament\Resources\VoteSites\VoteSiteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVoteSite extends EditRecord
{
    protected static string $resource = VoteSiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
