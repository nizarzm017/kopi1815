<?php

namespace App\Filament\Resources\ItemResource\RelationManagers;

use App\Enums\SatuanEnum;
use Dompdf\FrameDecorator\Text;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResepRelationManager extends RelationManager
{
    protected static string $relationship = 'resep';

    protected static ?string $recordTitleAttribute = 'resep';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([
                Select::make('item_id')
                    ->relationship('item', 'nama')
                    ->columnSpan(2)
                    ->required(),
                TextInput::make('qty')
                    ->numeric()
                    ->required(),
                Select::make('satuan')
                    ->options(SatuanEnum::toLabels())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.nama'),
                Tables\Columns\TextColumn::make('qty'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
