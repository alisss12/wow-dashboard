<?php

namespace App\Filament\Resources\LedgerTransactions\Schemas;

use Filament\Schemas\Schema;

class LedgerTransactionForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                \Filament\Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),
                \Filament\Forms\Components\Select::make('type')
                    ->options([
                        'deposit' => 'Deposit',
                        'withdrawal' => 'Withdrawal',
                        'commission' => 'Commission',
                        'adjustment' => 'Adjustment',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('reference_id')
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('reference_type')
                    ->maxLength(255),
            ]);
    }
}
