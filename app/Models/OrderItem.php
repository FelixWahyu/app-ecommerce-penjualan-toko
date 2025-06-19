<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_order',
        'kode_produk',
        'id_produk',
        'quantity',
        'jumlah_satuan',
        'jumlah_total'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }
}
