<?php

namespace App\Filament\Resources\RaidEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class RaidEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('service_category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Mythic+' => 'info',
                        'Raid' => 'primary',
                        default => 'gray',
                    })
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('instance_info')
                    ->label('Target / Level')
                    ->state(function (\App\Models\RaidEvent $record): string {
                        if ($record->service_category === 'Mythic+') {
                            return "{$record->dungeon_name} ({$record->mythic_plus_level})";
                        }
                        return "{$record->instance_name} ({$record->difficulty})";
                    })
                    ->searchable(['instance_name', 'dungeon_name', 'mythic_plus_level']),
                \Filament\Tables\Columns\TextColumn::make('region')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'EU' => 'success',
                        'NA', 'US' => 'info',
                        default => 'warning',
                    })
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('creator.name')
                    ->label('Requester')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('price_per_spot')
                    ->label('Price')
                    ->money('gold')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('scheduled_at')
                    ->dateTime()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved', 'open', 'running' => 'success',
                        'pending', 'scouting', 'locked' => 'warning',
                        'full' => 'danger',
                        'completed' => 'info',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\Action::make('approve')
                    ->label('Approve Run')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (\App\Models\RaidEvent $record) => auth()->user()->account_type === 'admin' && $record->status === 'pending')
                    ->action(fn (\App\Models\RaidEvent $record) => $record->update(['status' => 'approved']))
                    ->requiresConfirmation(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
