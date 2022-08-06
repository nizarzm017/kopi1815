<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Filament\Resources\PembelianResource\RelationManagers;
use App\Models\Item;
use App\Models\Pembelian;
use App\Models\Supplier;
use Closure;
use Illuminate\Support\Str;

use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $slug = 'pembelian';
    
    protected static ?string $label = 'Pembelian';

    protected static ?string $pluralLabel = 'Pembelian';

    protected static ?string $navigationLabel = 'Pembelian';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Transaksi Header')
                ->columns(2)
                ->schema([
                    TextInput::make('no_transaksi')
                        ->label('No Transaksi')
                        ->default(Pembelian::no_transaksi())
                        ->required(),
                    DatePicker::make('tanggal')
                        ->default(date('Y-m-d'))
                        ->required(),
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->default(auth()->user()->id)
                        ->disabled(),
                    Select::make('supplier_id')
                        ->relationship('supplier', 'nama')
                        ->searchable()
                        ->preload(),
                        Section::make('Detail')
                            ->columns(1)
                            ->schema([
                                Repeater::make('pembelian_detail')
                                    ->columns(4)
                                    ->relationship()
                                    ->schema([
                                        Select::make('item_id')
                                            ->relationship('item', 'nama')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function (Closure $set, $state){
                                                $set('harga', Str::slug(Item::find($state)->harga));
                                                $set('subtotal', Str::slug(Item::find($state)->harga));
                                            }),
                                        TextInput::make('harga')
                                            ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                                            ->reactive()
                                            ->afterStateUpdated(function (Closure $set, Closure $get, $state){
                                                $subtotal = Str::slug($state * $get('qty'));
                                                $set('subtotal', $subtotal);
                                            })
                                            ->required(),
                                        TextInput::make('qty')
                                            ->numeric()
                                            ->required()
                                            ->default(1)
                                            ->reactive()
                                            ->afterStateUpdated(function (Closure $set, Closure $get, $state){
                                                $subtotal = Str::slug($state * $get('harga'));
                                                $set('subtotal', $subtotal);
                                            }),
                                        TextInput::make('subtotal')
                                            ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                                            ->reactive()
                                            ->disabled()
                                    ])    
                                    ->createItemButtonLabel('Add Item'),
                                    Card::make()
                                    ->schema([
                                        Placeholder::make('total')
                                            ->content(function ($get, $set){
                                            $total = 0;
                                            foreach($get('pembelian_detail') as $data) {
                                                $total += (int)$data['subtotal'];
                                            }
                                            $set('total', Str::slug($total));
                                            return Str::slug($total);
                                        }),
                                        Hidden::make('total')
                                        ])
                                    ]),    
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }    
}
