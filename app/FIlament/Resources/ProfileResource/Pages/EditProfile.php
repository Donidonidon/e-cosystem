<?php

namespace App\Filament\Resources\ProfileResource\Pages;

use Filament\Actions;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProfileResource;
use Illuminate\Validation\ValidationException;

class EditProfile extends EditRecord
{
    protected static string $resource = ProfileResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
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
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
