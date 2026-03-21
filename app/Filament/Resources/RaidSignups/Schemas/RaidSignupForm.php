<?php

namespace App\Filament\Resources\RaidSignups\Schemas;

use Filament\Schemas\Schema;

class RaidSignupForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Select::make('raid_event_id')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Raid Run'),

                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->label('Account (optional — if known)'),

                \Filament\Forms\Components\TextInput::make('character_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Character Name'),

                \Filament\Forms\Components\Select::make('role')
                    ->options([
                        'tank'   => 'Tank',
                        'healer' => 'Healer',
                        'rdps'   => 'Ranged DPS',
                        'mdps'   => 'Melee DPS',
                        'dps'    => 'DPS (Any)',
                    ])
                    ->required(),

                \Filament\Forms\Components\TextInput::make('class')
                    ->required(),

                \Filament\Forms\Components\Textarea::make('notes')
                    ->label('Conditions / Requirements')
                    ->helperText('e.g. "Can bring 5 people", "Need 2 DPS slots", "Available after 20:00 UTC"')
                    ->rows(3)
                    ->columnSpanFull(),

                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending'  => 'Pending (Waiting for decision)',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                    ])
                    ->default('pending')
                    ->required(),

                \Filament\Forms\Components\Hidden::make('is_booster')->default(true),
                \Filament\Forms\Components\Hidden::make('character_guid')->default(0),
            ]);
    }
}
