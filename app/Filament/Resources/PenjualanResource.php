<?php

namespace App\Filament\Resources;

use Akaunting\Money\Money;
use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Penjualan;
use Closure;
use Illuminate\Support\Str;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $slug = 'penjualan';
    
    protected static ?string $label = 'Penjualan';

    protected static ?string $pluralLabel = 'Penjualan';

    protected static ?string $navigationLabel = 'Penjualan';

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
                        ->default(Penjualan::no_transaksi())
                        ->required(),
                    DatePicker::make('tanggal')
                        ->default(date('Y-m-d'))
                        ->required(),
                    Select::make('user_id')->relationship('user', 'name')->default(auth()->user()->id)->disabled(),
                    Radio::make('is_member')
                        ->label('Pembeli')
                        ->options([
                            Penjualan::$non_member  => 'Tidak Member',
                            Penjualan::$member      => 'Member',
                        ])
                        ->default(Penjualan::$non_member)
                        ->reactive(),
                    Select::make('member_id')
                        ->relationship(Penjualan::$member, 'nama')
                        ->searchable()
                        ->preload()
                        ->afterStateUpdated(function (Closure $set, $state){
                            $set('point', Member::find($state)->point()->sum('point'));
                        })
                        ->reactive()
                        // ini kd tahu kenapa jadi tebalik lh cari tahu sorang
                        ->hidden(fn (Closure $get) => $get('is_member') == Penjualan::$non_member),
                    TextInput::make('nama')
                        // ini kd tahu kenapa jadi tebalik lh cari tahu sorang
                        ->hidden(fn (Closure $get) => $get('is_member') == Penjualan::$member),
                    TextInput::make('point')
                        ->disabled()
                        // ini kd tahu kenapa jadi tebalik lh cari tahu sorang
                        ->hidden(fn (Closure $get) => $get('is_member') == Penjualan::$non_member)
                        
                            
                ]),

                Section::make('Detail')
                    ->columns(1)
                    ->schema([
                        Repeater::make('penjualan_detail')
                            ->columns(4)
                            ->relationship()
                            ->schema([
                                Select::make('menu_id')
                                    ->relationship('menu', 'nama')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set, $state){
                                        $set('harga', Str::slug(Menu::find($state)->harga));
                                        $set('subtotal', Str::slug(Menu::find($state)->harga));
                                        $set('../../total', Str::slug(Menu::find($state)->harga));
                                    }),
                                TextInput::make('harga')
                                    ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                                    ->reactive()
                                    ->disabled()
                                    ->required(),
                                TextInput::make('qty')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set, Closure $get, $state){
                                        $subtotal = Str::slug($state * $get('harga'));
                                        $set('subtotal', $subtotal);
                                        $set('../../total', Str::slug(($get('../i/subtotal') + $subtotal)));
                                    }),
                                TextInput::make('subtotal')
                                    ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                                    ->disabled()
                                    
                            ])
                    ]),
                Section::make('Pembayaran')
                    ->columns(2)
                    ->schema([
                        Select::make('pembayaran')
                            ->options([
                                Penjualan::$cash => 'Cash',
                                Penjualan::$non_cash => 'Non Cash',
                                Penjualan::$point => 'Point'
                            ])
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                                
                                if ((int) $state == Penjualan::$point && $get('is_member') == Penjualan::$member) {
                                    $member = Member::find($get('member_id'));
                                    
                                    if ( !empty($member) && $member->isPoint()) {
                                        $set('pembayaran', $state);
                                    } else {
                                        $set('pembayaran', Penjualan::$cash);
                                        Notification::make()
                                            ->title('Point tidak cukup')
                                            ->warning()
                                            ->send();
                                    }
                                }

                                if ($get('is_member') == Penjualan::$non_member && $state == Penjualan::$point) {
                                    $set('pembayaran', Penjualan::$cash);
                                    Notification::make()
                                        ->title('Pembeli bukan member')
                                        ->warning()
                                        ->send();
                                }
                            })
                            ->default(Penjualan::$cash),
                        TextInput::make('total')
                            ->required()
                            ->disabled()
                            ->reactive()
                            ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                            ->hidden(fn (Closure $get) => (int)$get('pembayaran') == Penjualan::$point),
                        TextInput::make('bayar')
                            ->required()
                            ->default(0)
                            ->reactive()
                            ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                            ->afterStateUpdated(function (Closure $set, Closure $get, $state){
                                $set('kembalian', Str::slug($get('total') - $state));
                            })
                            ->hidden(fn (Closure $get) => (int)$get('pembayaran') !== Penjualan::$cash),
                        TextInput::make('kembalian')
                            ->required()
                            ->disabled()
                            ->reactive()
                            ->default(0)
                            ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                            ->hidden(fn (Closure $get) => (int)$get('pembayaran') !== Penjualan::$cash),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }    
}
