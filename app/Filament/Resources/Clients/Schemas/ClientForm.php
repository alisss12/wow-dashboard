<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('character_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('realm')
                    ->required()
                    ->maxLength(255),
                Select::make('region')
                    ->options([
                        'US' => 'US',
                        'EU' => 'EU',
                        'KR' => 'KR',
                        'TW' => 'TW',
                    ])
                    ->required(),
                TextInput::make('discord_id')
                    ->maxLength(255),
                TextInput::make('type')
                    ->maxLength(255),
                TextInput::make('orders_count')
                    ->numeric()
                    ->default(0),
                TextInput::make('total_spent')
                    ->numeric()
                    ->default(0),
                TextInput::make('flag_reason')
                    ->maxLength(255),
            ]);
    }
}
