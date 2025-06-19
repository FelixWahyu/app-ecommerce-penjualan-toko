<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['nama_unit', 'slug', 'is_active'];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_unit');
    }
}
