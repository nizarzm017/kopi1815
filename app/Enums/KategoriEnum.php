<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self makanan()
 * @method static self minuman()
 */

 class KategoriEnum extends Enum
 {

    public static function kategori(): array
    {
        return [
            'makanan' => 'Makanan',
            'minuman' => 'Minuman'
        ];
    }

    public static function colorKategori(): array
    {
        return [
            'primary' => 'makanan',
            'danger',
        ];
    }
 }
?>