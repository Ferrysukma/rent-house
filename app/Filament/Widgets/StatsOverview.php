<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {   
        $listing        = Listing::whereMonth("created_at", Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        $transaction    = Transaction::where('status', 'approved')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        $prevTransaction    = Transaction::where('status', 'approved')->whereMonth('created_at', Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->subMonth()->year)->count();
        $percentageTransaction  = $transaction - $prevTransaction / ($transaction + $prevTransaction) / 2 * 100;
        $revenueThisMonth   = Transaction::where('status', 'approved')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('total_price');
        return [
            Stat::make('New listting of the month', $listing . ' Listings'),
            Stat::make('Transaction of the month', $transaction . ' Transactions')->description($percentageTransaction > 0 ? ($percentageTransaction . '% incrased') : ($percentageTransaction . '% decresed')),
            Stat::make('Revenue this month', Number::currency($revenueThisMonth,'USD')),

        ];
    }
}
