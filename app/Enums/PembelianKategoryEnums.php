<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self minuman()
 * @method static self makanan()
 * @method static self barang()
 */

 class PembelianKategoryEnums extends Enum
 {

    public static function kategori(): array
    {
        return [
            'makanan' => 'Makanan',
            'minuman' => 'Minuman',
            'barang' => 'Barang'
        ];
    }

    public static function colorKategori(): array
    {
        return [
            'primary' => 'makanan',
            'secondary' => 'minuman',
            'danger',
        ];
    }
 }
?>