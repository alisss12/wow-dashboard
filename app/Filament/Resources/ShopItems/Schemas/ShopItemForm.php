<?php

namespace App\Filament\Resources\ShopItems\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ShopItemForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('category_id')
                    ->required()
                    ->numeric(),
                TextInput::make('price_vote')
                    ->numeric(),
                TextInput::make('price_donate')
                    ->numeric(),
                Select::make('type')
                    ->options([
            'item' => 'Item',
            'gold' => 'Gold',
            'level' => 'Level',
            'mount' => 'Mount',
            'custom' => 'Custom',
            'service' => 'Service',
            'faction_change' => 'Faction change',
            'race_change' => 'Race change',
        ])
                    ->required(),
                TextInput::make('data'),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(-1),
                FileUpload::make('image')
                    ->image(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('active')
                    ->required(),
            ]);
    }
}
