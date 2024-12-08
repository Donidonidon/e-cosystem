<?php

namespace App\Filament\Resources\AttendenceResource\Widgets;

use App\Models\Attendence;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class UserAttendencesStatisticsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsersToday = User::all()->count();
        $totalAlpa = Attendence::whereDate('created_at', today())->whereNull('start_time')->count();

        $jumlahAlpa = $totalUsersToday - $totalAlpa;
        return [
            Stat::make('Total Absen Hari Ini', Attendence::whereDate('created_at', today())->count()),
            Stat::make('Total Terlambat Hari Ini', Attendence::whereDate('created_at', today())
                ->whereColumn('start_time', '>', 'schedule_start_time')
                ->count()),
            Stat::make('Total Alpa Hari Ini', $jumlahAlpa),
        ];
    }
}
