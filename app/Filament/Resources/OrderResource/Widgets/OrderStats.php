<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Status Proses', Order::query()->where('status', 'proses')->count()),
            Stat::make('Status Pending', Order::query()->where('status_pembayaran', 'pending')->count())
        ];
    }
}
