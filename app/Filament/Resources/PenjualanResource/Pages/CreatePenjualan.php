<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;
    
    protected function handleRecordCreation(array $data): Model
    {
        unset($data['point']);

        $create = $this->getModel()::create($data);
        
        return $create;
    }

    protected function afterCreate(): void
    {
        $point = $this->record->addPoint();
    }


}
