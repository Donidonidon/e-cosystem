<?php

namespace App\Filament\Resources\AttendenceResource\Pages;

use App\Filament\Resources\AttendenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendence extends EditRecord
{
    protected static string $resource = AttendenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
