<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\Menu;
use App\Models\Penjualan;
use App\Models\Supplier;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {   
        $menu       = Menu::all()->count();
        $member     = Member::all()->count();
        $supplier   = Supplier::all()->count();
        $penjualan  = Penjualan::getSumByDate()->count();
        return [
            Card::make('Menu', $menu),
            Card::make('Members', $member),
            Card::make('Supplier', $supplier),
            Card::make('Transaksi Bulan Ini', $penjualan),
        ];
    }
}
