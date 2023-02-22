<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Enums\PembelianKategoryEnums;
use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use App\Models\Pembelian;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $navigationIcon = 'bx-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode')
                    ->required()
                    ->default(fn(Closure $get) => 'M'.Item::kode_item(PembelianKategoryEnums::makanan()))
                    ->unique(),
                TextInput::make('nama')
                    ->required(),               
                TextInput::make('harga')
                    ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                    ->required(),
                Radio::make('kategori')
                    ->options(PembelianKategoryEnums::kategori())
                    ->lazy()
                    ->default(PembelianKategoryEnums::makanan())
                    ->afterStateUpdated(function(Closure $set, $state){
                        if($state == PembelianKategoryEnums::makanan()){
                            return $set('kode', 'M'. Item::kode_item($state));
                        }
                        if($state == PembelianKategoryEnums::minuman()){
                            return $set('kode', 'I'. Item::kode_item($state));
                        }
                        return $set('kode', 'B'. Item::kode_item($state));
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->sortable(),
                TextColumn::make('nama'),
                TextColumn::make('qty'),
                TextColumn::make('harga')
                    ->money('idr', true),
            ])
            ->filters([
                
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('Cetak Laporan')
                    ->extraViewData([
                        'title' => 'Item'
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageItems::route('/'),
        ];
    }    
}
