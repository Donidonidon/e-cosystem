<?php

namespace App\Filament\Resources\PengkinianResource\Pages;

use App\Filament\Resources\PengkinianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengkinian extends EditRecord
{
    protected static string $resource = PengkinianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
