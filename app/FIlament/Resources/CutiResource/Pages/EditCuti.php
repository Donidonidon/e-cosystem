<?php

namespace App\Filament\Resources\CutiResource\Pages;

use App\Filament\Resources\CutiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCuti extends EditRecord
{
    protected static string $resource = CutiResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['approved_by_hrd'] == true && $data['approved_by_leader'] == true && $data['approved_by_direksi'] == true) {
            $data['status'] = 'approved';
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
