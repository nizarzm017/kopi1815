<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table    = 'pegawai';
    protected $guarded  = [];

    static $jenis_kelamin       = array('Laki-laki', 'Perempuan');
    static $status_perkawinan   = array('Belum Nikah', 'Nikah');
    static $agama               = array('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu');
    static $jabatan             = array('Owner', 'Staff', 'Kasir', 'Admin', 'Staff Kitchen');
}
