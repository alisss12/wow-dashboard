<?php

namespace App\Filament\Resources\RaidSignups\Tables;

use App\Models\RaidSignup;
use App\Notifications\BoosterApplicationAccepted;
use App\Notifications\BoosterApplicationDeclined;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification;

class RaidSignupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('event.title')
                    ->label('Raid / Protocol')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                \Filament\Tables\Columns\TextColumn::make('user.name')
                    ->label('Applicant')
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('character_name')
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('notes')
                    ->label('Conditions')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->notes),

                \Filament\Tables\Columns\TextColumn::make('agreed_price')
                    ->label('Gold')
                    ->money('gold')
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accepted' => 'success',
                        'pending'  => 'warning',
                        'waitlist' => 'info',
                        'declined' => 'danger',
                        default    => 'gray',
                    })
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                        'waitlist' => 'Waitlist',
                    ]),

                SelectFilter::make('is_booster')
                    ->label('Application Type')
                    ->options([
                        '1' => 'Booster Applications',
                        '0' => 'Client Bookings',
                    ]),
            ])
            ->recordActions([
                Action::make('selectAndNotify')
                    ->label('✓ Select & Notify')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (RaidSignup $record): bool => $record->is_booster && $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Select This Booster')
                    ->modalDescription('This will accept this booster and automatically decline all other pending applicants for the same raid. They will all be notified.')
                    ->action(function (RaidSignup $signup) {
                        $raid = $signup->event;

                        // 1. Accept this booster
                        $signup->update(['status' => 'accepted']);
                        if ($signup->user) {
                            $signup->user->notify(new BoosterApplicationAccepted($raid));
                        }

                        // 2. Link Booster to the Event
                        $raid->update([
                            'booster_user_id'    => $signup->user_id,
                            'assigned_leader_id' => $signup->user_id,
                            'price_per_spot'     => $signup->agreed_price ?: $raid->price_per_spot,
                        ]);

                        // 3. Advance Lifecycle Status
                        // If it's an M+ Queue run, move it to 'running' so it shows in Booster Dashboard alerts
                        if ($raid->is_queue) {
                            $raid->update(['status' => 'running']);
                        } else {
                            // For regular raids, move to approved/scheduled
                            $raid->update(['status' => 'approved']);
                        }

                        // 4. Decline all other pending boosters for this raid
                        $others = $raid->signups()
                            ->where('is_booster', true)
                            ->where('status', 'pending')
                            ->where('id', '!=', $signup->id)
                            ->with('user')
                            ->get();

                        $raid->signups()
                            ->where('is_booster', true)
                            ->where('status', 'pending')
                            ->where('id', '!=', $signup->id)
                            ->update(['status' => 'declined']);

                        foreach ($others as $other) {
                            if ($other->user) {
                                $other->user->notify(new BoosterApplicationDeclined($raid));
                            }
                        }
                    })
                    ->successNotificationTitle('Booster selected. Others notified.'),

                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
