<?php

namespace App\Filament\Resources\CutiResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\CutiResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCuti extends EditRecord
{
    protected static string $resource = CutiResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!isset($data['approved_by_direksi'])) {
            $data['approved_by_direksi'] = false;
        }

        if ($data['approved_by_direksi']) {
            $data['status'] = 'approved';
            $data['approval_by_direksi_id'] = Auth::user()->id;
        }

        if (!isset($data['approved_by_hrd'])) {
            $data['approved_by_hrd'] = false;
        }

        if ($data['approved_by_hrd']) {
            $data['approval_by_hrd_id'] = Auth::user()->id;
        }

        return $data;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Cuti updated')
            ->body('The cuti has been updated successfully.');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
