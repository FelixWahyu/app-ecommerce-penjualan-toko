<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Order;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\OrderResource;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;

class LatestOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Order Id')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('grand_total')
                    ->money('IDR'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'baru' => 'info',
                        'proses' => 'warning',
                        'dikirim' => 'info',
                        'sukses' => 'success',
                        'canceled' => 'danger'
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'baru' => 'heroicon-m-shopping-bag',
                        'proses' => 'heroicon-m-arrow-path',
                        'dikirim' => 'heroicon-m-truck',
                        'sukses' => 'heroicon-m-check-badge',
                        'canceled' => 'heroicon-m-x-circle'
                    })
                    ->sortable(),
                TextColumn::make('id_metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status_pembayaran')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'sukses' => 'success',
                        'gagal' => 'danger'
                    }),
                TextColumn::make('tanggal_pemesanan')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->translatedFormat('d F Y')),
            ])
            ->actions([
                Action::make('Lihat Pesanan')
                    ->url(fn(Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
