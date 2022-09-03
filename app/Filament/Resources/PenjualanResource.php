<?php

namespace App\Filament\Resources;

use Akaunting\Money\Money;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Enums\KategoriEnum;
use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Penjualan;
use Closure;
use Illuminate\Support\Str;
use Filament\Forms;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $slug = 'penjualan';
    
    protected static ?string $label = 'Penjualan';

    protected static ?string $pluralLabel = 'Penjualan';

    protected static ?string $navigationLabel = 'Penjualan';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationIcon = 'bi-shop';

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
                        ->required()
                        ->disabled(),
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->default(
                            auth()->user()->id
                        )
                        ->disabled(),
                    Radio::make('is_member')
                        ->label('Pembeli')
                        ->options([
                            Penjualan::$member => 'Member',
                            Penjualan::$non_member  => 'Tidak Member',
                        ])
                        ->default(Penjualan::$non_member)
                        ->reactive(),
                    Select::make('member_id')
                        ->relationship('member', 'nama')
                        ->label('Nama')
                        ->searchable()
                        ->preload()
                        ->afterStateUpdated(function (Closure $set, $state){
                            $totalPoint = Member::find($state)->point()->sum('point');
                            $set('point', $totalPoint);
                        })
                        ->reactive()
                        // ini kd tahu kenapa jadi tebalik lh cari tahu sorang
                        ->hidden(fn (Closure $get) => $get('is_member') == Penjualan::$non_member),
                    TextInput::make('nama')
                        // ini kd tahu kenapa jadi tebalik lh cari tahu sorang
                        ->hidden(fn (Closure $get) => $get('is_member') == Penjualan::$member),
                    TextInput::make('point')
                        ->disabled()
                        ->default(0)
                        ->afterStateHydrated(function (Closure $set, Closure $get, $state, ?Penjualan $record){
                            $set(
                                'point',
                                $record?->member()->point()->where('created_at', '<', $record->created_at)->sum('point')
                            );
                            // $set(
                            //     'point', 
                            //     Member::find($get('member_id'))
                            //         ?->point()
                            //         ->where('created_at', '<', $record->created_at)
                            //         ->sum('point')
                            //     );
                        })
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

                                        foreach($get('penjualan_detail') as $data) {
                                            $total += (int)$data['subtotal'];
                                        }

                                        $set('total', Str::slug($total));
                                        return Str::slug($total);
                                    })
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
                                    $details = $get('penjualan_detail');
                                    $jumlah_pembelian = collect($details)->sum('qty');
                                    
                                    if ($member->isPoint($jumlah_pembelian)) {
                                        foreach ($details as $detail) {
                                            $menu = Menu::find((int)$detail['menu_id']);
                                            if ($menu->kategori == KategoriEnum::makanan()) {
                                                Notification::make()
                                                    ->title('Point hanya bisa membeli minuman')
                                                    ->warning()
                                                    ->send();
                                                return $set('pembayaran', Penjualan::$cash);
                                            }
                                        }
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
                            ->default(0)
                            ->disabled()
                            ->reactive()
                            ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                            ->hidden(fn (Closure $get) => (int)$get('pembayaran') == Penjualan::$point),
                        Hidden::make('total')
                            ->required()
                            ->default(0)
                            ->reactive()
                            ->hidden(fn (Closure $get) => (int)$get('pembayaran') != Penjualan::$point),
                        TextInput::make('bayar')
                            ->required()
                            ->default(0)
                            ->reactive()
                            ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                            ->afterStateUpdated(function (Closure $set, Closure $get, $state){
                                $set('kembalian', Str::slug($state - $get('total')));
                            })
                            ->hidden(fn (Closure $get) => (int)$get('pembayaran') !== Penjualan::$cash),
                        TextInput::make('kembalian')
                            ->required()
                            ->disabled()
                            ->reactive()
                            ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.' , 0))
                            ->default(0)
                            ->hidden(fn (Closure $get) => (int)$get('pembayaran') !== Penjualan::$cash),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_transaksi'),
                TextColumn::make('user.name'),
                TextColumn::make('tanggal'),
                TextColumn::make('penjualan_detail_sum_qty')->sum('penjualan_detail', 'qty')->label("Kuantitas"),
                TextColumn::make('total')
            ])
            ->filters([
                SelectFilter::make('is_member')
                    ->options([
                        Penjualan::$member => 'Member',
                        Penjualan::$non_member => 'Bukan Member'
                    ])
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('Cetak Laporan')
                    ->extraViewData([
                        'title' => 'Penjualan'
                    ])
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
