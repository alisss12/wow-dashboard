<?php

namespace App\Filament\Resources\BoosterApplications;

use App\Filament\Resources\BoosterApplications\Pages\CreateBoosterApplication;
use App\Filament\Resources\BoosterApplications\Pages\EditBoosterApplication;
use App\Filament\Resources\BoosterApplications\Pages\ListBoosterApplications;
use App\Filament\Resources\BoosterApplications\Schemas\BoosterApplicationForm;
use App\Filament\Resources\BoosterApplications\Tables\BoosterApplicationsTable;
use App\Models\BoosterApplication;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class BoosterApplicationResource extends Resource
{
    protected static ?string $model = BoosterApplication::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-check';

    protected static string|UnitEnum|null $navigationGroup = 'Applications';

    protected static ?string $recordTitleAttribute = 'character_name';

    public static function form(Schema $schema): Schema
    {
        return BoosterApplicationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BoosterApplicationsTable::configure($table);
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
            'index' => ListBoosterApplications::route('/'),
            'create' => CreateBoosterApplication::route('/create'),
            'edit' => EditBoosterApplication::route('/{record}/edit'),
        ];
    }
}
