<?php

namespace App\Models;

use App\Enums\KategoriEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table    = 'menu';
    protected $guarded  = [];
    protected $casts = [
        'kategori' => KategoriEnum::class,
    ];

    public function penjualan_detail(){
        return $this->hasMany(PenjualanDetail::class);
    }

    public function resep(){
        return $this->hasMany(Resep::class);
    }
    
}
