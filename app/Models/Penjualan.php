<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table    = 'penjualan';
    protected $guarded  = [];
    static $cash        = 1;
    static $non_cash    = 2;
    static $point       = 3;

    static $member      = 1;
    static $non_member  = 0;

    static $bayar_point = 250;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function menu(){
        return $this->belongsTo(Menu::class);
    }

    public function member(){
        return $this->belongsTo(Member::class);
    }

    public function member_point(){
        return $this->hasOne(MemberPoint::class);
    }

    public function penjualan_detail(){
        return $this->hasMany(PenjualanDetail::class);
    }

    public function addPoint(): void
    {
        if(! $this->is_member) return;
        if ($this->pembayaran == self::$point) {
            # code...
            $detail = PenjualanDetail::where('penjualan_id', $this->id)->get();

            
            $qty = collect($detail)->sum('qty');
            
            MemberPoint::create([
                'member_id'     => $this->member_id,
                'penjualan_id'  => $this->id,
                'point'         => (int) ($qty * -(self::$bayar_point)),  
            ]);
            
            return;
        }
        $points = $this->total / 1000;

        MemberPoint::create([
            'member_id'     => $this->member_id,
            'penjualan_id'  => $this->id,
            'point'         => $points,  
        ]);

        // $this->member_point()->create([
        //     'member_id'     => $this->member_id,
        //     'penjualan_id'  => $this->id,
        //     'point'         => $points,
        // ]);
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

    static function getSumByDate() {
        $month  = date('m');
        $year   = date('Y');
        $query  = self::select('id')->whereMonth('created_at', $month)->whereYear('created_at', $year);
        return $query;
    }

    static function getTransaksiByDate() {
        $month  = date('m');
        $year   = date('Y');
        $query  = self::select('*')->whereMonth('created_at', $month)->whereYear('created_at', $year);
        return $query;
    }

    static function getBestSeller(){
        $today      = Carbon::now();
        $firstDay   = $today->firstOfMonth();
        $ids        = static::whereBetween('created_at', [$firstDay, $today])->pluck('id');
        $query      = PenjualanDetail::selectRaw('id, menu_id, count(qty) as jumlah')->groupBy('menu_id')->whereIn('penjualan_id', $ids);
        // $ids        = self::whereColumn('id', $ids);
        // $query      = PenjualanDetail::select('barang_id')->select(PenjualanDetail::raw('count(qty) as jumlah, qty'))->whereIn('transaksi_id', $ids)->groupBy('barang_id')->orderByDesc('jumlah')->get();

        return $query;
    }
}
