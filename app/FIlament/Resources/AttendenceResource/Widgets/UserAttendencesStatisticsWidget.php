<?php

namespace App\Filament\Resources\AttendenceResource\Widgets;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendence;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class UserAttendencesStatisticsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsersToday = User::all()->count();
        $totalAlpaAll = Attendence::whereDate('created_at', today())->count();
        $jumlahAlpaAll = $totalUsersToday - $totalAlpaAll;

        if (Auth::user()->hasAnyRole(['direksi', 'hrd', 'super_admin'])) {
            return [
                Stat::make('Total Absen Hari Ini', Attendence::whereDate('created_at', today())->count()),
                Stat::make('Total Terlambat Hari Ini', Attendence::whereDate('created_at', today())
                    ->whereColumn('start_time', '>', 'schedule_start_time')
                    ->count()),
                Stat::make('Total Alpa Hari Ini', $jumlahAlpaAll),
            ];
        } else {
            return [
                Stat::make(
                    'Total Terlambat Bulan Ini',
                    Attendence::whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->whereColumn('start_time', '>', 'schedule_start_time')
                        ->where('user_id', Auth::user()->id)
                        ->count()
                )
            ];
        }

        return [];
    }

    // public static function canView(): bool
    // {
    //     return Auth::user()->hasAnyRole(['direksi', 'hrd', 'super_admin']);
    // }
}
