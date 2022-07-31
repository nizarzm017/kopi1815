<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Member;
use App\Models\Penjualan;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
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
                            'nonmember' => 'Tidak Member',
                            'member' => 'Member',
                        ])
                        ->default('nonmember')
                        ->reactive(),
                    Select::make('member_id')
                        ->relationship('member', 'nama')
                        ->searchable()
                        ->preload()
                        ->afterStateUpdated(function (Closure $set, $state){
                            $set('point', Member::find($state)->point()->sum('point'));
                        })
                        ->reactive()
                        // ini kd tahu kenapa jadi tebalik lh cari tahu sorang
                        ->hidden(fn (Closure $get) => $get('is_member') == 'nonmember'),
                    TextInput::make('nama')
                        // ini kd tahu kenapa jadi tebalik lh cari tahu sorang
                        ->hidden(fn (Closure $get) => $get('is_member') == 'member'),
                    TextInput::make('point')
                        ->disabled()
                        // ini kd tahu kenapa jadi tebalik lh cari tahu sorang
                        ->hidden(fn (Closure $get) => $get('is_member') == 'nonmember')
                        
                            
                ]),
                Section::make('Pembayaran')
                    ->columns(2)
                    ->schema([
                        Select::make('payment')
                            ->options([
                                Penjualan::$cash => 'Cash',
                                Penjualan::$non_cash => 'Non Cash',
                                Penjualan::$point => 'Point'
                            ])
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                                
                                if ((int) $state == Penjualan::$point && $get('is_member') == 'member') {
                                    $member = Member::find($get('member_id'));
                                    
                                    if ( !empty($member) && $member->isPoint()) {
                                        $set('payment', $state);
                                    } else {
                                        $set('payment', Penjualan::$cash);
                                        Notification::make()
                                            ->title('Point tidak cukup')
                                            ->warning()
                                            ->send();
                                    }
                                }

                                if ($get('is_member') == 'nonmember' && $state == Penjualan::$point) {
                                    $set('payment', Penjualan::$cash);
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
                            ->hidden(fn (Closure $get) => (int)$get('payment') == Penjualan::$point),
                        TextInput::make('bayar')
                            ->required()
                            ->hidden(fn (Closure $get) => (int)$get('payment') !== Penjualan::$cash),
                        TextInput::make('kembalian')
                            ->required()
                            ->disabled()
                            ->hidden(fn (Closure $get) => (int)$get('payment') !== Penjualan::$cash),
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
