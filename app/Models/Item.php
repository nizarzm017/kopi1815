<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pembelian_detail(){
        return $this->hasMany(PembelianDetail::class);
    }

    public function resep(){
        return $this->hasMany(Resep::class);
    }

    static function kode_item(){
        $number     = 1;
        $pembelian  = static::latest()->first();

        if (!empty($pembelian)) {
            $kode   = explode("/[a-z]+./", $pembelian['kode']);
            $number = $pembelian['kode'] + 1;
        }

        return str_pad(
            $number,
            '0',
            STR_PAD_LEFT
        );
    }

}
