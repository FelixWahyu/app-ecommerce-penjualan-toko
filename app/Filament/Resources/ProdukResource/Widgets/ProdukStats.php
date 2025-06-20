<?php

namespace App\Filament\Resources\ProdukResource\Widgets;

use App\Models\Produk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProdukStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Produk Limit', Produk::query()->where('stock', '<=', 5)->count()),
        ];
    }
}
