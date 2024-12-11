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
use App\Models\Attendence;
use App\Models\Schedule;

class ListAttendences extends ListRecords
{
    protected static string $resource = AttendenceResource::class;

    protected function getHeaderActions(): array
    {
        $is_admin = Auth::user()->hasAnyRole(['super_admin', 'hrd']);

        // Ambil user ID
        $userId = Auth::id();

        // Waktu pulang yang diizinkan (misalnya, pukul 17:00)
        $allowedTime = now()->setTime(17, 0, 0);
        $currentTime = now();

        // Cek apakah user sudah absen masuk hari ini
        $hasCheckedIn = Attendence::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->exists();

        // dd($hasCheckedIn);

        // Tombol presensi dengan kondisi disabled
        $presensi = Action::make('Presensi')
            ->label('Presensi')
            ->icon('heroicon-s-plus')
            ->url(route('presensi'))
            ->color('success')
            ->disabled($hasCheckedIn && $currentTime->lessThan($allowedTime)) // Disable jika sudah absen dan belum waktu pulang
            ->tooltip(
                !$hasCheckedIn
                    ? 'Silakan absen masuk terlebih dahulu'
                    : ($currentTime->lessThan($allowedTime) ? 'Belum waktunya untuk pulang' : null)
            );

        $export = Action::make('Export Attendence')
            ->icon('heroicon-o-printer')
            ->color('info')
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

                return Excel::download(new AttendenceExport($startDate, $endDate), $startDate . ' hingga ' . $endDate . ' Presensi.xlsx');
            });

        if ($is_admin) {
            return [$export, $presensi];
        }

        return [$presensi];
    }



    protected function getHeaderWidgets(): array
    {
        return [
            AttendenceResource\Widgets\UserAttendencesStatisticsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AttendenceResource\Widgets\AttendencesOverview::class,
        ];
    }
}
