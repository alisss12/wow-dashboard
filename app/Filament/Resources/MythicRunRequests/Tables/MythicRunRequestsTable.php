<?php

namespace App\Filament\Resources\MythicRunRequests\Tables;

use App\Models\MythicRunRequest;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class MythicRunRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('client.name')
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => MythicRunRequest::STATUS_WAITING,
                        'info' => MythicRunRequest::STATUS_GROUPING,
                        'primary' => MythicRunRequest::STATUS_RUNNING,
                        'success' => MythicRunRequest::STATUS_COMPLETED,
                    ]),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        MythicRunRequest::STATUS_WAITING => 'Waiting',
                        MythicRunRequest::STATUS_GROUPING => 'Grouping',
                        MythicRunRequest::STATUS_RUNNING => 'Running',
                        MythicRunRequest::STATUS_COMPLETED => 'Completed',
                    ]),
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
