<?php

namespace App\Filament\Resources\VoteSites\Pages;

use App\Filament\Resources\VoteSites\VoteSiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVoteSites extends ListRecords
{
    protected static string $resource = VoteSiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
