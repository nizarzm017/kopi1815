<?php

namespace App\Filament\Resources;

use Akaunting\Money\Money;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Enums\KategoriEnum;
use App\Filament\Resources\ItemResource\RelationManagers\ResepRelationManager;
use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
use App\Models\Resep;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;
    
    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $slug = 'menu';
    
    protected static ?string $label = 'Menu';

    protected static ?string $pluralLabel = 'Menu';

    protected static ?string $navigationLabel = 'Menu';

    protected static ?string $navigationIcon = 'bx-food-menu';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make()
            ->columns(1)    
            ->schema([
                TextInput::make('nama')
                ->required(),
                TextInput::make('harga')
                ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                ->required(),
                Radio::make('kategori')
                ->options(KategoriEnum::kategori())
                ->required()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama'),
                TextColumn::make('harga')
                    ->formatStateUsing(function($record) {
                        return Money::IDR($record->harga, true)->formatWithoutZeroes();
                    }),
                BadgeColumn::make('kategori')
                    ->formatStateUsing(
                        fn($state) => ucfirst($state->value)
                    )
                    ->colors([
                        'success' => KategoriEnum::minuman(),
                        'primary' => KategoriEnum::makanan(),
                    ])
            ])
            ->filters([
                //
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('Cetak Laporan')
                    ->extraViewData([
                        'title' => 'Menu'
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
        
    public static function getRelations(): array
    {
        return [
            ResepRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }    
}
