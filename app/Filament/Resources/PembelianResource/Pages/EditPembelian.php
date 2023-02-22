<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembelian extends EditRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
