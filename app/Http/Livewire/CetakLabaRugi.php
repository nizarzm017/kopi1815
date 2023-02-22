<?php

namespace App\Http\Livewire;

use App\Models\Pembelian;
use App\Models\Penjualan;
use Livewire\Component;

class CetakLabaRugi extends Component
{

    public $penjualan;
    public $pembelian;
    public $dari;
    public $sampai;
    public $laba;

    public function mount(){
        $post = (object) $_POST;
        $this->dari      = $post->dari;
        $this->sampai    = $post->sampai;
        $this->penjualan = Penjualan::getTotalPenjualan($post->dari, $post->sampai);
        $this->pembelian = Pembelian::getTotalPembelian($post->dari, $post->sampai);
        $this->laba      = $this->penjualan - $this->pembelian;
    }

    // public function render()
    // {
    //     return view('livewire.cetak-laba-rugi')->layout('layouts.blank');
    // }
    public function render()
    {
        return view('livewire.cetak-laba-rugi')->layout('layouts.blank');
    }

}
