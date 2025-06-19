<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use App\Models\Produk;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        $order = $this->record;

        // Loop semua item dalam pesanan
        foreach ($order->items as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                // Kurangi stok produk
                $produk->stock -= $item->quantity;
                $produk->save();
            }
        }
    }
}
