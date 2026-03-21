<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('gateway')
                    ->required(),
                TextInput::make('amount_real')
                    ->required()
                    ->numeric(),
                TextInput::make('points_given')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required(),
                TextInput::make('transaction_id')
                    ->required(),
            ]);
    }
}
