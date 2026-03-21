<?php

namespace App\Filament\Resources\MythicRunRequests;

use App\Filament\Resources\MythicRunRequests\Pages\CreateMythicRunRequest;
use App\Filament\Resources\MythicRunRequests\Pages\EditMythicRunRequest;
use App\Filament\Resources\MythicRunRequests\Pages\ListMythicRunRequests;
use App\Filament\Resources\MythicRunRequests\Schemas\MythicRunRequestForm;
use App\Filament\Resources\MythicRunRequests\Tables\MythicRunRequestsTable;
use App\Models\MythicRunRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class MythicRunRequestResource extends Resource
{
    protected static ?string $model = MythicRunRequest::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bolt';

    protected static string|UnitEnum|null $navigationGroup = 'M+';

    protected static ?string $navigationLabel = 'Run Requests';

    public static function form(Schema $schema): Schema
    {
        return MythicRunRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MythicRunRequestsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMythicRunRequests::route('/'),
            'create' => CreateMythicRunRequest::route('/create'),
            'edit' => EditMythicRunRequest::route('/{record}/edit'),
        ];
    }
}
