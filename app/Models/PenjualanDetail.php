<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;
    protected $table    = 'penjualan_detail';
    protected $guarded  = [];

    public function penjualan(){
        return $this->belongsTo(Penjualan::class);
    }

    public function menu(){
        return $this->belongsTo(Menu::class);
    }
}
