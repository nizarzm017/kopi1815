<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use App\Models\Penjualan;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;
    
    protected function handleRecordCreation(array $data): Model
    {
        unset($data['point']);
        if ($data['pembayaran'] != Penjualan::$cash) {
            $data['bayar'] = $data['total'];
        }
        return$this->getModel()::create($data);
    }

    protected function afterCreate(): void
    {
        $point = $this->record->addPoint();
    }

    protected function getRedirectUrl(): string
    {
        return route('nota', ['penjualan' => $this->record]);
    }
}
