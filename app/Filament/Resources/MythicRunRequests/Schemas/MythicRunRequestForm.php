<?php

namespace App\Filament\Resources\MythicRunRequests\Schemas;

use App\Models\MythicRunRequest;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class MythicRunRequestForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->required(),
                Select::make('client_user_id')
                    ->relationship('client', 'name')
                    ->required(),
                Select::make('status')
                    ->options([
                        MythicRunRequest::STATUS_WAITING => 'Waiting',
                        MythicRunRequest::STATUS_GROUPING => 'Grouping',
                        MythicRunRequest::STATUS_RUNNING => 'Running',
                        MythicRunRequest::STATUS_COMPLETED => 'Completed',
                    ])
                    ->required(),
                TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->prefix('$'),
            ]);
    }
}
