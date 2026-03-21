<?php

namespace App\Filament\Resources\RaidSignups;

use App\Filament\Resources\RaidSignups\Pages\CreateRaidSignup;
use App\Filament\Resources\RaidSignups\Pages\EditRaidSignup;
use App\Filament\Resources\RaidSignups\Pages\ListRaidSignups;
use App\Filament\Resources\RaidSignups\Schemas\RaidSignupForm;
use App\Filament\Resources\RaidSignups\Tables\RaidSignupsTable;
use App\Models\RaidSignup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class RaidSignupResource extends Resource
{
    protected static ?string $model = RaidSignup::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    protected static string|UnitEnum|null $navigationGroup = 'Raids';

    protected static ?string $recordTitleAttribute = 'character_name';

    public static function form(Schema $schema): Schema
    {
        return RaidSignupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RaidSignupsTable::configure($table);
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
            'index' => ListRaidSignups::route('/'),
            'create' => CreateRaidSignup::route('/create'),
            'edit' => EditRaidSignup::route('/{record}/edit'),
        ];
    }
}
