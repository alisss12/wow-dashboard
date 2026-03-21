<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Gold Moved', number_format(\App\Models\LedgerTransaction::sum('amount')) . 'g')
                ->description('Cumulative transaction volume')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Active Raids', \App\Models\RaidEvent::whereIn('status', ['open', 'approved', 'full'])->count())
                ->description('Runs currently in recruitment')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
            Stat::make('Pending Payouts', \App\Models\PaymentRequest::where('status', 'pending')->count())
                ->description('Requests awaiting processing')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('warning'),
        ];
    }
}
