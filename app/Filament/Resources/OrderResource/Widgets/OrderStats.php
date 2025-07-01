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
            Stat::make('New Order', Order::where('status', 'baru')->count() . ' Order'),
            Stat::make('Total Order', Order::count() . ' Order'),
            Stat::make('Total Pendapatan', 'Rp ' . number_format(
                Order::where('status_pembayaran', 'sukses')->sum('grand_total')
            )),
            Stat::make('Total Pengguna', User::count() . ' orang'),
        ];
    }
}
