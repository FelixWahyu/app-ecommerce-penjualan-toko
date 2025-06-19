<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['id_order', 'phone', 'alamat', 'kota', 'provinsi', 'kode_pos'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
