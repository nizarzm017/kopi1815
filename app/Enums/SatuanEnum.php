<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self gram()
 * @method static self pcs()
 */

 class SatuanEnum extends Enum
 {

    public static function kategori(): array
    {
        return [
            'gram' => 'G',
            'pcs' => 'Pcs'
        ];
    }

    public static function colorKategori(): array
    {
        return [
            'primary' => 'gram',
            'danger',
        ];
    }
 }
?>