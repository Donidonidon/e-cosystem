<?php

namespace App\Filament\Resources\AttendenceResource\Widgets;

use App\Models\User;
use Filament\Tables;
use App\Models\Attendence;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class AttendencesOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Daftar Karyawan Tidak Hadir';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                //user yang belum absen
                User::whereDoesntHave('attendences', function ($query) {
                    $query->whereDate('created_at', today());
                }),
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Karyawan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('profile.jabatan.name')
                    ->label('Jabatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('profile.divisi.name')
                    ->label('Divisi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('profile.kantor.name')
                    ->label('Kantor')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'WFA' => 'info',
                        default => 'gray',
                    }),
            ]);
    }
    public static function canView(): bool
    {
        return Auth::user()->hasAnyRole(['direksi', 'hrd', 'super_admin']);
    }
}
