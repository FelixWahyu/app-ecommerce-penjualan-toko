<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['nama_category', 'slug', 'is_active'];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_category');
    }
}
