<?php

namespace App\Filament\Resources\PengkinianResource\Pages;

use Filament\Actions;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PengkinianResource;

class CreatePengkinian extends CreateRecord
{
    protected static string $resource = PengkinianResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        return $data;
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
