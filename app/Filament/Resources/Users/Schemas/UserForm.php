<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('game_account_id')
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                TextInput::make('vote_points')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('donation_points')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('referral_code'),
                TextInput::make('referred_by')
                    ->numeric(),
                \Filament\Forms\Components\Select::make('account_type')
                    ->options([
                        'user' => 'Normal User',
                        'advertiser' => 'Advertiser',
                        'booster' => 'Booster',
                        'admin' => 'Admin',
                    ])
                    ->default('user')
                    ->required(),
                TextInput::make('balance')
                    ->numeric()
                    ->prefix('$')
                    ->default(0),
                DateTimePicker::make('last_vote_at'),
            ]);
    }
}
