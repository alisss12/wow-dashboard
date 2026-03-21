<?php

namespace App\Filament\Resources\BoostingServices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BoostingServiceForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('category')
                    ->required(),
                TextInput::make('base_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('required_boosters')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('max_clients')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('required_tanks')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('required_healers')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('required_dps')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
