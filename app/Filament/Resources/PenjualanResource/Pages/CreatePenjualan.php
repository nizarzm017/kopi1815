<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;
    
    protected function handleRecordCreation(array $data): Model
    {
        // dd($data);
        return $this->getModel()::create($data);
    }
}
