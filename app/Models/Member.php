<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $guarded = [];

    static $min = 250;

    public function point()
    {
        return $this->hasMany(MemberPoint::class);
    }

    public function isPoint($jumlah_pembelian)
    {
        return $this->point()->sum('point') >= (static::$min * $jumlah_pembelian); 
    }

    // public function getPointbeforeDate()
    // {
    //     return $this->point()->whereDate('created_at', '<', )->sum('point'); 
    // }

    public function penjualan(){
        return $this->hasMany(Penjualan::class);
    }
}
