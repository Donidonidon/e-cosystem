<?php

namespace App\Filament\Resources\PengkinianResource\Pages;

use App\Filament\Resources\PengkinianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePengkinian extends CreateRecord
{
    protected static string $resource = PengkinianResource::class;
    protected static bool $canCreateAnother = false;
}
