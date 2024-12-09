<?php

namespace App\Filament\Resources\AttendenceResource\Widgets;

use Filament\Tables;
use App\Models\Attendence;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AttendencesOverview extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendence::whereDate('created_at', today())->whereNull('start_time'),
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Karyawan')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.profile.jabatan.name')
                    ->label('Jabatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.profile.divisi.name')
                    ->label('Divisi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_late')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->isLate() ? 'Terlambat' : 'Tepat Waktu';
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Tepat Waktu' => 'success',
                        'Terlambat' => 'danger',
                    })
                    ->description(fn(Attendence $record): string => $record->onTimeOrLate() ?: ''),
                Tables\Columns\TextColumn::make('kantor.name')
                    ->label('Absen di Kantor')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'WFA' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Waktu Datang'),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Waktu Pulang'),
                Tables\Columns\TextColumn::make('work_duration')
                    ->label('Durasi Kerja')
                    ->getStateUsing(function ($record) {
                        return $record->workDuration();
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
