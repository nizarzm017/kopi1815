<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;
    protected $table    = 'pembelian';
    protected $guarded  = [];

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function item(){
        return $this->belongsTo(Item::class);
    }
    
    public function pembelian_detail(){
        return $this->hasMany(PembelianDetail::class);
    }

    public function total_pembelian()
    {
        return $this->pembelian_detail()->sum('qty');
    }

    static function no_transaksi(){
        $number     = 1;
        $pembelian  = static::latest()->first();

        if (!empty($pembelian)) {
            $number = $pembelian['no_transaksi'] + 1;
        }

        return str_pad(
            $number,
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}
