<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Filament\Resources\PegawaiResource\RelationManagers;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $slug = 'pegawai';
    
    protected static ?string $label = 'Pegawai';

    protected static ?string $pluralLabel = 'Pegawai';

    protected static ?string $navigationLabel = 'Pegawai';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')->required(),
                Select::make('jenis_kelamin')->options(Pegawai::$jenis_kelamin)->required(),
                TextInput::make('tempat')->label('Tempat Lahir')->required(),
                DatePicker::make('tanggal_lahir')->required(),
                Select::make('agama')->options(Pegawai::$agama)->required(),
                Textarea::make('alamat')->required(),
                TextInput::make('no_hp')->required(),
                Select::make('status_perkawinan')->options(Pegawai::$status_perkawinan)->required(),
                Select::make('jabatan')->options(pegawai::$jabatan)->required(),
                DatePicker::make('mulai_bekerja')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('nama'),
                TextColumn::make('jenis_kelamin')->enum(Pegawai::$jenis_kelamin),
                TextColumn::make('no_hp'),
                TextColumn::make('jabatan'),
                TextColumn::make('mulai_bekerja')
            ])
            ->filters([
                //
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
            'index' => Pages\ManagePegawais::route('/'),
        ];
    }    
}
