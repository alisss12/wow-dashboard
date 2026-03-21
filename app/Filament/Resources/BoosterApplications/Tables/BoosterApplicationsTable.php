<?php

namespace App\Filament\Resources\BoosterApplications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BoosterApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User Account')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('character_name')
                    ->label('Character')
                    ->searchable(),
                TextColumn::make('realm')
                    ->searchable(),
                TextColumn::make('class')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Warrior' => 'gray',
                        'Paladin' => 'pink',
                        'Hunter' => 'success',
                        'Rogue' => 'warning',
                        'Priest' => 'info',
                        'Death Knight' => 'danger',
                        'Shaman' => 'primary',
                        'Mage' => 'info',
                        'Warlock' => 'danger',
                        'Monk' => 'success',
                        'Druid' => 'warning',
                        'Demon Hunter' => 'danger',
                        'Evoker' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
