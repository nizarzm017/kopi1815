<?php

namespace App\Filament\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Enums\KategoriEnum;
use App\Models\Menu;
use App\Models\Penjualan;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class BestSeller extends Page implements HasTable
{
    use HasPageShield;

    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.best-seller';

    protected static ?string $navigationGroup = 'Laporan';

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }
    
    protected function getTableQuery(): Builder
    {
        return Menu::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('nama')
                ->label('Nama Menu'),
            TextColumn::make('harga')
                ->label('Harga'),
            BadgeColumn::make('kategori')
                ->label('Kategori')
                ->formatStateUsing(
                    fn($state) => ucfirst($state->value)
                )
                ->colors([
                    'success' => KategoriEnum::minuman(),
                    'primary' => KategoriEnum::makanan(),
                ]),

            TextColumn::make('penjualan_detail_sum_qty')
                ->sum('penjualan_detail', 'qty')
                ->label('Total Penjualan'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('kategori')
                ->options([
                    'makanan' => 'Makanan',
                    'minuman' => 'Minuman',

                ])
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            FilamentExportHeaderAction::make('Cetak')
                ->extraViewData([
                    'title' => 'Best Seller'
                ])
        ];
    }
}
