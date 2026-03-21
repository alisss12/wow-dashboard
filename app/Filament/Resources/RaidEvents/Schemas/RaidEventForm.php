<?php

namespace App\Filament\Resources\RaidEvents\Schemas;

use Filament\Schemas\Schema;

class RaidEventForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Select::make('service_category')
                    ->options([
                        'Raid' => 'Raid Execution',
                        'Mythic+' => 'Mythic+ Operation',
                    ])
                    ->default('Raid')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('is_queue', $state === 'Mythic+')),

                \Filament\Forms\Components\Hidden::make('is_queue')
                    ->default(false),

                \Filament\Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                \Filament\Forms\Components\TextInput::make('instance_name')
                    ->label('Raid Name')
                    ->visible(fn (callable $get) => $get('service_category') === 'Raid')
                    ->required(fn (callable $get) => $get('service_category') === 'Raid')
                    ->maxLength(255),

                \Filament\Forms\Components\Select::make('difficulty')
                    ->label('Raid Difficulty')
                    ->options(['10 Normal' => '10 Normal', '10 Heroic' => '10 Heroic', '25 Normal' => '25 Normal', '25 Heroic' => '25 Heroic', 'Mythic' => 'Mythic'])
                    ->visible(fn (callable $get) => $get('service_category') === 'Raid')
                    ->required(fn (callable $get) => $get('service_category') === 'Raid')
                    ->default('25 Normal'),

                \Filament\Forms\Components\TextInput::make('dungeon_name')
                    ->label('Dungeon Name')
                    ->visible(fn (callable $get) => $get('service_category') === 'Mythic+')
                    ->required(fn (callable $get) => $get('service_category') === 'Mythic+')
                    ->maxLength(255),

                \Filament\Forms\Components\TextInput::make('mythic_plus_level')
                    ->label('Key Level')
                    ->visible(fn (callable $get) => $get('service_category') === 'Mythic+')
                    ->required(fn (callable $get) => $get('service_category') === 'Mythic+')
                    ->placeholder('e.g. +15'),

                \Filament\Forms\Components\DateTimePicker::make('scheduled_at')
                    ->required(),

                \Filament\Forms\Components\TextInput::make('duration_hours')
                    ->numeric()
                    ->default(3),

                \Filament\Forms\Components\TextInput::make('leader_name')
                    ->maxLength(255),

                \Filament\Forms\Components\Select::make('leader_user_id')
                    ->relationship('leader', 'name')
                    ->searchable()
                    ->preload(),

                \Filament\Forms\Components\TextInput::make('max_players')
                    ->numeric()
                    ->default(fn (callable $get) => $get('service_category') === 'Mythic+' ? 5 : 25)
                    ->required(),

                \Filament\Forms\Components\TextInput::make('min_ilvl_requirement')
                    ->numeric(),

                \Filament\Schemas\Components\Grid::make(3)
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('required_tanks')->numeric()->default(2)->label('Tanks Needed'),
                        \Filament\Forms\Components\TextInput::make('required_healers')->numeric()->default(5)->label('Healers Needed'),
                        \Filament\Forms\Components\TextInput::make('required_dps')->numeric()->default(18)->label('DPS Needed'),
                    ])
                    ->visible(fn (callable $get) => $get('service_category') === 'Raid'),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending'   => 'Pending Approval',
                        'approved'  => 'Approved (Visible)',
                        'open'      => 'Open (Booking)', 
                        'locked'    => 'Locked', 
                        'full'      => 'Full', 
                        'completed' => 'Completed (Process Payouts)',
                        'cancelled' => 'Cancelled'
                    ])
                    ->default('pending')
                    ->disabled(fn () => auth()->user()->account_type !== 'admin')
                    ->dehydrated() // Ensures the value is saved even if disabled
                    ->required(),
                \Filament\Forms\Components\TextInput::make('price_per_spot')
                    ->numeric()
                    ->prefix('$') // or whatever currency indicator
                    ->helperText('Keep blank if free'),
                \Filament\Forms\Components\Select::make('booster_user_id')
                    ->relationship('booster', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Assigned Booster'),
                \Filament\Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                \Filament\Schemas\Components\Section::make('Dynamic Capacity (Mythic Focus)')
                    ->description('Define custom slots for Classes, Tokens, or Armor types. Leave empty to use global armor totals.')
                    ->schema([
                        \Filament\Forms\Components\KeyValue::make('dynamic_slots')
                            ->keyLabel('Identity (e.g. Mage, Zenith)')
                            ->valueLabel('Total Capacity')
                            ->valuePlaceholder('0')
                            ->addActionLabel('Add Identity Slot'),
                    ])
                    ->collapsible(),
                \Filament\Schemas\Components\Section::make('Gold Economy Protocol')
                    ->description('Configuration for distribution and collection')
                    ->schema([
                        \Filament\Forms\Components\Select::make('collection_type')
                            ->options([
                                'collector' => 'Central Gold Collector',
                                'advertiser' => 'Advertiser (Direct Handover)'
                            ])
                            ->default('collector')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('management_cut_percentage')
                            ->numeric()
                            ->default(10)
                            ->suffix('%')
                            ->required()
                            ->label('House Management Cut'),
                        \Filament\Forms\Components\Toggle::make('payout_distributed')
                            ->label('Payout Processed')
                            ->helperText('Automatically checked when run is marked as completed')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(2),
            ]);
    }
}
