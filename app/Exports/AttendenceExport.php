<?php

namespace App\Exports;

use App\Models\Attendence;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AttendenceExport implements FromQuery, WithHeadings
{

    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = Carbon::parse($startDate)->startOfDay();  // Set waktu mulai menjadi jam 00:00:00
        $this->endDate = Carbon::parse($endDate)->endOfDay();        // Set waktu akhir menjadi jam 23:59:59

    }

    public function query()
    {
        return Attendence::query()
            ->whereBetween('attendences.created_at', [$this->startDate, $this->endDate])
            ->join('users', 'attendences.user_id', '=', 'users.id')
            ->join('kantors', 'attendences.kantor_id', '=', 'kantors.id')
            ->join('schedules', 'attendences.user_id', '=', 'schedules.user_id')
            ->join('shifts', 'schedules.shift_id', '=', 'shifts.id')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->leftJoin('divisis', 'profiles.divisi_id', '=', 'divisis.id')
            ->leftJoin('jabatans', 'profiles.jabatan_id', '=', 'jabatans.id')
            ->select([
                DB::raw('DATE_FORMAT(attendences.created_at, "%d-%m-%Y") as tanggal'),
                'users.name as nama',
                'divisis.name as divisi',
                'jabatans.name as jabatan',
                'kantors.name as nama_kantor',
                'attendences.start_time',
                'attendences.end_time',
                'attendences.deskripsi',
                DB::raw('CASE WHEN attendences.start_time > shifts.start_time THEN "Terlambat" ELSE "Tepat Waktu" END as keterangan_terlambat'),
            ])
            ->orderBy('attendences.created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama',
            'Divisi',
            'Jabatan',
            'Tempat Absen',
            'Jam Masuk',
            'Jam Keluar',
            'Keterangan',
            'Keterangan Terlambat'
        ];
    }
}
