<?php

namespace App\Models;

use App\Enums\PembelianKategoryEnums;
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

    static function kode_item($kategori){
        $number     = 1;
        $makanan  = static::where('kategori', PembelianKategoryEnums::makanan())->latest()->first();
        $minuman  = static::where('kategori', PembelianKategoryEnums::minuman())->latest()->first();
        $barang  = static::where('kategori', PembelianKategoryEnums::barang())->latest()->first();

        if(!empty($makanan) && $kategori == PembelianKategoryEnums::makanan()){
            $kode = filter_var($makanan['kode'], FILTER_SANITIZE_NUMBER_INT);
            $number = $kode + 1;
        }
        
        if(!empty($minuman) && $kategori == PembelianKategoryEnums::minuman()){
            $kode = filter_var($minuman['kode'], FILTER_SANITIZE_NUMBER_INT);
            $number = $kode + 1;
        }
        
        if(!empty($barang) && $kategori == PembelianKategoryEnums::barang()){
            $kode = filter_var($barang['kode'], FILTER_SANITIZE_NUMBER_INT);
            $number = $kode + 1;
        }
        
        return $number;
    }

}
