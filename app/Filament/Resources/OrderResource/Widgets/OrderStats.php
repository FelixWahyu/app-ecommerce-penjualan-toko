<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use App\Models\Produk;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Order', Order::query()->where('status', 'baru')->count()),
            Stat::make('Total Order', Order::count()),
            Stat::make('Total Pendapatan', Order::query()->where('status_pembayaran', 'sukses')->sum('grand_total')),
            // Stat::make('Total Pelanggan', User::query()->where('role', 'pelanggan')->count()),
        ];
    }
}
