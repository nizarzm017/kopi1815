<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;
    protected $table    = 'pembelian_detail';
    protected $guarded  = [];

    public function pembelian(){
        return $this->belongsTo(Pembelian::class);
    }

    public function item(){
        return $this->belongsTo(Item::class);
    }
}
