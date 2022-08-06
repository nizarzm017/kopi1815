<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPoint extends Model
{
    use HasFactory;
    protected $guarded  = [];
     
    public function penjualan(){
        return $this->hasOne(Penjualan::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
