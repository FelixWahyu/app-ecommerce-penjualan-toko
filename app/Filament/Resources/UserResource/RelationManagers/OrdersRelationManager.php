<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use League\CommonMark\Node\Query\OrExpr;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // 
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->label('Order Id')
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
                    ->badge(),
                TextColumn::make('tanggal_pemesanan')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->translatedFormat('d F Y')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('Lihat pesanan')
                    ->url(fn(Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                    ->color('info')
                    ->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
