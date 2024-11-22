<?php

namespace App\Filament\Resources\ListingsResource\Pages;

use App\Filament\Resources\ListingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListings extends EditRecord
{
    protected static string $resource = ListingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
