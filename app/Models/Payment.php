<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['nama_bank', 'nomor_rekening', 'nama_pemilik', 'image'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_metode_pembayaran');
    }
}
