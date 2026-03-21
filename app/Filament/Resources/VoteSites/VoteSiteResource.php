<?php

namespace App\Filament\Resources\VoteSites;

use App\Filament\Resources\VoteSites\Pages\CreateVoteSite;
use App\Filament\Resources\VoteSites\Pages\EditVoteSite;
use App\Filament\Resources\VoteSites\Pages\ListVoteSites;
use App\Filament\Resources\VoteSites\Schemas\VoteSiteForm;
use App\Filament\Resources\VoteSites\Tables\VoteSitesTable;
use App\Models\VoteSite;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class VoteSiteResource extends Resource
{
    protected static ?string $model = VoteSite::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-hand-thumb-up';

    protected static string|UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return VoteSiteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VoteSitesTable::configure($table);
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
            'index' => ListVoteSites::route('/'),
            'create' => CreateVoteSite::route('/create'),
            'edit' => EditVoteSite::route('/{record}/edit'),
        ];
    }
}
