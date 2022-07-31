<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageItems extends ManageRecords
{
    protected static string $resource = ItemResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
