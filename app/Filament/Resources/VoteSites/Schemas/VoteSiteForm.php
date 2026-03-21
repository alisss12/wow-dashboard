<?php

namespace App\Filament\Resources\VoteSites\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VoteSiteForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('url')
                    ->url()
                    ->required(),
                TextInput::make('reward_points')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('cooldown_hours')
                    ->required()
                    ->numeric()
                    ->default(12),
                FileUpload::make('image_url')
                    ->image(),
                Toggle::make('active')
                    ->required(),
            ]);
    }
}
