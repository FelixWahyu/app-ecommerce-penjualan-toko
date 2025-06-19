<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'pending' => Tab::make()->query(fn($query) => $query->where('status_pembayaran', 'pending')),
            'baru' => Tab::make()->query(fn($query) => $query->where('status', 'baru')),
            'proses' => Tab::make()->query(fn($query) => $query->where('status', 'proses')),
            'dikirim' => Tab::make()->query(fn($query) => $query->where('status', 'dikirim')),
            'sukses' => Tab::make()->query(fn($query) => $query->where('status', 'sukses')),
            'canceled' => Tab::make()->query(fn($query) => $query->where('status', 'canceled')),
        ];
    }
}
