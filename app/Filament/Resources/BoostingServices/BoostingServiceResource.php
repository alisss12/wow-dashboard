<?php

namespace App\Filament\Resources\BoostingServices;

use App\Filament\Resources\BoostingServices\Pages\CreateBoostingService;
use App\Filament\Resources\BoostingServices\Pages\EditBoostingService;
use App\Filament\Resources\BoostingServices\Pages\ListBoostingServices;
use App\Filament\Resources\BoostingServices\Schemas\BoostingServiceForm;
use App\Filament\Resources\BoostingServices\Tables\BoostingServicesTable;
use App\Models\BoostingService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class BoostingServiceResource extends Resource
{
    protected static ?string $model = BoostingService::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rocket-launch';

    protected static string|UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return BoostingServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BoostingServicesTable::configure($table);
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
            'index' => ListBoostingServices::route('/'),
            'create' => CreateBoostingService::route('/create'),
            'edit' => EditBoostingService::route('/{record}/edit'),
        ];
    }
}
