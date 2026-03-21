<?php

namespace App\Filament\Resources\LedgerTransactions;

use App\Filament\Resources\LedgerTransactions\Pages\CreateLedgerTransaction;
use App\Filament\Resources\LedgerTransactions\Pages\EditLedgerTransaction;
use App\Filament\Resources\LedgerTransactions\Pages\ListLedgerTransactions;
use App\Filament\Resources\LedgerTransactions\Schemas\LedgerTransactionForm;
use App\Filament\Resources\LedgerTransactions\Tables\LedgerTransactionsTable;
use App\Models\LedgerTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class LedgerTransactionResource extends Resource
{
    protected static ?string $model = LedgerTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|UnitEnum|null $navigationGroup = 'Financial';

    protected static ?string $recordTitleAttribute = 'id';

    public static function canCreate(): bool
    {
        return auth()->user()->account_type === 'admin';
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return in_array($user->account_type, ['admin', 'booster', 'advertiser']);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->account_type === 'booster') {
            $query->where('booster_id', $user->id);
        } elseif ($user->account_type === 'advertiser') {
            $query->where('advertiser_id', $user->id);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return LedgerTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LedgerTransactionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLedgerTransactions::route('/'),
            'create' => CreateLedgerTransaction::route('/create'),
            'edit' => EditLedgerTransaction::route('/{record}/edit'),
        ];
    }
}
