<?php

namespace App\Filament\Resources\AttendenceResource\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Actions\Action;
use App\Exports\AttendenceExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Tables\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AttendenceResource;

class ListAttendences extends ListRecords
{
    protected static string $resource = AttendenceResource::class;

    protected function getHeaderActions(): array
    {
        $is_admin = Auth::user()->hasRole('super_admin');
        $export_presensi = Action::make('Export Data')->label('Export Data')->icon('heroicon-o-printer')
            ->url(route('attendence-export'))
            ->color('info');
        $presensi = Action::make('Presensi')->label('Presensi')->icon('heroicon-s-plus')
            ->url(route('presensi'))
            ->color('success');

        $coba = Action::make('Export Attendence')
            ->icon('heroicon-o-printer')
            ->form([
                Forms\Components\DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->required(),
            ])
            ->action(function (array $data) {
                $startDate = $data['start_date'];
                $endDate = $data['end_date'];

                return Excel::download(new AttendenceExport($startDate, $endDate), 'attendence.xlsx');
            });

        if ($is_admin) {
            return [$coba, $presensi];
        }
        return [$presensi];
    }
}
