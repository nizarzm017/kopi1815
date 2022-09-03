<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
