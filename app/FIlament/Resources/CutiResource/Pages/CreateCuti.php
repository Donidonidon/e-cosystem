<?php

namespace App\Filament\Resources\CutiResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\CutiResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateCuti extends CreateRecord
{
    protected static string $resource = CutiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;

        $startDate = Carbon::parse($data['start_date']); // Konversi string menjadi Carbon
        $endDate = Carbon::parse($data['end_date']);

        $data['jumlah_hari'] = $startDate->diffInDays($endDate) + 1;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
