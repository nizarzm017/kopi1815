<?php

namespace App\Http\Livewire;

use App\Models\Penjualan;
use Livewire\Component;

class Nota extends Component
{
    // public function render()
    // {
    //     return view('livewire.nota');
    // }

    public $penjualan;
    public $point;
    public $points;
    public $non_cash;
    public $beginning_points;

    public function mount(Penjualan $penjualan){
        $this->point = Penjualan::$point;
        $this->no_cash = Penjualan::$non_cash;
        $this->cash = Penjualan::$cash;
        if ($penjualan->is_member) {
            $this->beginning_points = $penjualan->member()->first()->point()->where('created_at', '<', $penjualan->created_at)->sum('point');
            $this->total_points = $this->beginning_points + $penjualan->member_point?->point;
        }
    }

    public function render()
    {
        return view('livewire.nota')->layout('layouts.blank');
    }
}
