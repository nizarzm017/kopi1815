<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PenjualanResource;
use App\Models\Penjualan;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PenjualanBulanIni extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableQuery(): Builder
    {
        return Penjualan::getTransaksiByDate();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('no_transaksi')
                ->searchable()
                ->sortable(),
            TextColumn::make('user.name')
                ->searchable()
                ->sortable(),
            TextColumn::make('created_at')
                ->label('Tanggal Penjualan')
                ->date()
                ->sortable(),
            TextColumn::make('penjualan_detail_sum_qty')
                ->sum('penjualan_detail', 'qty')
                ->label("Kuantitas")
                ->searchable()
                ->sortable(),
            TextColumn::make('total')
                ->searchable()
                ->sortable(),
        ];
    }
}
