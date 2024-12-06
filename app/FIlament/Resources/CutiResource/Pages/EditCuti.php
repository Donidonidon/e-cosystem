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
        // Ambil data asli dari database
        $existingData = $this->record;

        // Pastikan field `approved_by_leader`, `approved_by_hrd`, dan `approved_by_direksi` ada
        if (!isset($data['approved_by_leader'])) {
            $data['approved_by_leader'] = $existingData->approved_by_leader ?? false; // Pertahankan nilai asli jika ada
        }

        if (!isset($data['approved_by_hrd'])) {
            $data['approved_by_hrd'] = $existingData->approved_by_hrd ?? false; // Pertahankan nilai asli
        }

        if (!isset($data['approved_by_direksi'])) {
            $data['approved_by_direksi'] = $existingData->approved_by_direksi ?? false; // Pertahankan nilai asli
        }

        // Proses persetujuan oleh Leader
        if ($data['approved_by_leader']) {
            $data['approval_by_leader_id'] = $existingData->approval_by_leader_id ?? Auth::user()->id;
        }

        // Proses persetujuan oleh HRD (hanya jika Leader sudah menyetujui)
        if ($data['approved_by_hrd']) {
            if (!$data['approved_by_leader']) {
                // Berikan notifikasi error jika Leader belum menyetujui
                Notification::make()
                    ->title('Gagal Menyetujui')
                    ->body('Leader harus menyetujui terlebih dahulu sebelum HRD dapat menyetujui.')
                    ->danger()
                    ->send();

                // Kembalikan data tanpa menyimpan perubahan
                return $data;
            }

            $data['approval_by_hrd_id'] = $existingData->approval_by_hrd_id ?? Auth::user()->id;
        }

        // Proses persetujuan oleh Direksi (hanya jika HRD sudah menyetujui)
        if ($data['approved_by_direksi']) {
            if (!$data['approved_by_hrd']) {
                // Berikan notifikasi error jika HRD belum menyetujui
                Notification::make()
                    ->title('Gagal Menyetujui')
                    ->body('HRD harus menyetujui terlebih dahulu sebelum Direksi dapat menyetujui.')
                    ->danger()
                    ->send();

                // Kembalikan data tanpa menyimpan perubahan
                return $data;
            }

            $data['status'] = 'approved'; // Ubah status menjadi approved
            $data['approval_by_direksi_id'] = Auth::user()->id; // Set ID Direksi yang menyetujui
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
