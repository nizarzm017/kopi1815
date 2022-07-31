<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMembers extends ManageRecords
{
    protected static string $resource = MemberResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
