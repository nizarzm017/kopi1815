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

    public function isPoint()
    {
        return $this->point()->sum('point') >= static::$min; 
    }

    public function penjualan(){
        return $this->hasMany(Penjualan::class);
    }
}
