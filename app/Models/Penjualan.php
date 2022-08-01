<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table    = 'penjualan';
    protected $guarded  = [];
    static $cash = 1;
    static $non_cash = 2;
    static $point = 3;

    static $member = 1;
    static $non_member = 2;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function menu(){
        return $this->belongsTo(Menu::class);
    }

    public function member(){
        return $this->belongsTo(Member::class);
    }

    public function penjualan_detail(){
        return $this->hasMany(PenjualanDetail::class);
    }

    static function no_transaksi(){
        $number     = 1;
        $penjualan  = static::latest()->first();

        if(!empty($penjualan)){
            $number = $penjualan['no_transaksi'] + 1;
        }
        
        return str_pad(
         $number , 5, '0' , STR_PAD_LEFT
        );
        

    }

}
