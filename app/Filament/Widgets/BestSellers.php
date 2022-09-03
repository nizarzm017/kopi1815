<?php

namespace App\Filament\Widgets;

use App\Enums\KategoriEnum;
use App\Models\Penjualan;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class BestSellers extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }
    
    protected function getTableQuery(): Builder
    {
        return Penjualan::getBestSeller();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('menu.nama')
                ->label('Nama Menu'),
            TextColumn::make('menu.harga')
                ->label('Harga'),
            BadgeColumn::make('menu.kategori')
                ->label('Kategori')
                ->formatStateUsing(
                    fn($state) => ucfirst($state->value)
                )
                ->colors([
                    'success' => KategoriEnum::minuman(),
                    'primary' => KategoriEnum::makanan(),
                ]),

            TextColumn::make('jumlah')
                ->label('Total Penjualan'),
        ];
    }
}
