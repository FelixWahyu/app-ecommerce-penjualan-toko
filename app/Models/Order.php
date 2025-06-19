<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_user',
        'tanggal_pemesanan',
        'id_metode_pembayaran',
        'status_pembayaran',
        'status',
        'currency',
        'metode_pengiriman',
        'ongkos_kirim',
        'keterangan',
        'grand_total',
        // 'phone',
        // 'alamat_pengiriman',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'id_order');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id_order');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
