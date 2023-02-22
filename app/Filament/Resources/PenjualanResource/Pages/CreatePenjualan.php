<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
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
        $datas = $this->record->penjualan_detail;
        foreach($datas as $data){
            $reseps = Menu::find($data->menu_id)->resep;
            foreach ($reseps as $resep) {
                $item = Item::find($resep->item_id);
                $qty  = $item->qty - $resep->qty;
                $item->update([
                    'qty'  => $qty
                ]);
            }
            
        }
    }

    protected function getRedirectUrl(): string
    {
        return route('nota', ['penjualan' => $this->record]);
    }
}
