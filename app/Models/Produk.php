<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'id_category',
        'harga_beli',
        'harga_jual',
        'id_unit',
        'stock',
        'deskripsi',
        'image_produk',
        'is_active',
    ];

    // protected $casts = [
    //     'image_produk' => 'array',
    // ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'id_produk');
    }
}
