<?php

namespace App\Filament\Resources\ProfileResource\Pages;

use Filament\Actions;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ProfileResource;

class CreateProfile extends CreateRecord
{
    protected static string $resource = ProfileResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['signature']) && strpos($data['signature'], 'data:image/png;base64,') === 0) {
            // Decode Base64 dan hapus prefix data URL
            $base64String = str_replace('data:image/png;base64,', '', $data['signature']);
            $decodedImage = base64_decode($base64String);

            // Buat nama file unik
            $fileName = 'signatures/' . uniqid() . '.png';

            // Simpan file ke disk (misalnya di folder 'public')
            Storage::disk('public')->put($fileName, $decodedImage);

            // Simpan path file ke kolom signature
            $data['signature'] = $fileName;

            $data['is_completed'] = true;
        }

        $data['user_id'] = Auth::user()->id;
        return $data;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Profile created')
            ->body('The user profile has been saved successfully.');
    }

    protected function afterCreate(): void
    {
        // Mendapatkan data profile yang baru saja dibuat
        $profile = $this->record;

        // Otomatis membuat data schedule
        Schedule::create([
            'user_id' => $profile->user_id,
            'shift_id' => 1,
            'kantor_id' => $profile->kantor_id,
        ]);
    }
}
