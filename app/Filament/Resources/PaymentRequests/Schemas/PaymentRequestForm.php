<?php

namespace App\Filament\Resources\PaymentRequests\Schemas;

use Filament\Schemas\Schema;

class PaymentRequestForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Select::make('type')
                    ->options([
                        'deposit' => 'Deposit (Add Funds)',
                        'withdrawal' => 'Withdrawal (Cash Out)',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                \Filament\Forms\Components\Select::make('gateway')
                    ->options([
                        'paypal' => 'PayPal',
                        'crypto' => 'Cryptocurrency (USDT/BTC)',
                        'bank' => 'Bank Transfer'
                    ])
                    ->required(),
                \Filament\Forms\Components\Textarea::make('details')
                    ->label('Payment Details / Wallet Address')
                    ->helperText('Where should we send the funds, or how did you pay us?')
                    ->columnSpanFull()
                    ->required(),
                \Filament\Forms\Components\Textarea::make('admin_notes')
                    ->label('Admin Notes')
                    ->hidden(fn () => auth()->user()->account_type !== 'admin')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'declined' => 'Declined'
                    ])
                    ->default('pending')
                    ->hidden(fn () => auth()->user()->account_type !== 'admin'),
            ]);
    }
}
