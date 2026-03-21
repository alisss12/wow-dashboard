<?php

namespace App\Filament\Resources\RaidEvents;

use App\Filament\Resources\RaidEvents\Pages\CreateRaidEvent;
use App\Filament\Resources\RaidEvents\Pages\EditRaidEvent;
use App\Filament\Resources\RaidEvents\Pages\ListRaidEvents;
use App\Filament\Resources\RaidEvents\Schemas\RaidEventForm;
use App\Filament\Resources\RaidEvents\Tables\RaidEventsTable;
use App\Models\RaidEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class RaidEventResource extends Resource
{
    protected static ?string $model = RaidEvent::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|UnitEnum|null $navigationGroup = 'Raids';

    protected static ?string $recordTitleAttribute = 'character_name';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        // Advertisers shouldn't see the backend Raid Panel, they use the frontend
        return in_array($user->account_type, ['admin', 'booster']);
    }

    public static function canCreate(): bool
    {
        // Both Admin and Booster can create runs
        return in_array(auth()->user()->account_type, ['admin', 'booster']);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // If not an admin, only show runs where this user is the assigned booster
        if ($user->account_type !== 'admin') {
            $query->where('booster_user_id', $user->id);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return RaidEventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RaidEventsTable::configure($table);
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
            'index' => ListRaidEvents::route('/'),
            'create' => CreateRaidEvent::route('/create'),
            'edit' => EditRaidEvent::route('/{record}/edit'),
        ];
    }
}
