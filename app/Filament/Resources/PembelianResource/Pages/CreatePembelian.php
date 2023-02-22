<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use App\Models\Item;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $datas = $this->record->pembelian_detail;
        foreach($datas as $data){
            $item = Item::find($data->item_id);
            $qty  = $item->qty + $data->qty;
            $item->update([
                'qty'  => $qty
            ]);
        }
    }
}
