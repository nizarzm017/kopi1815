<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Shield\RoleResource\Pages\ListRoles;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use STS\FilamentImpersonate\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('General')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\TextInput::make('email')->required()->email(),
                ]),
                Section::make('Password')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('password')->type('password')->required()
                    ->same('passwordConfirmation')
                    ->rules([
                        Password::min(8)
                        ->letters()
                    ])
                    ->dehydrated(fn ($state) => $state)
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                    TextInput::make('passwordConfirmation')
                    ->type('password')
                    ->required()
                    ->dehydrated(false),
                    MultiSelect::make('roles')->relationship('roles', 'name')
                        ->label('Roles')
                        ->preload(),
                ])
            ]);
    }

    public static function table(Table $table): table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('roles.name'),
              
            ])
            ->prependActions([
            ])
            ->filters([
                //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
