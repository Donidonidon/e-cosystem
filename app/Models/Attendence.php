<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kantor_id',
        'schedule_latitude',
        'schedule_longitude',

        'schedule_start_time', //get shift pagi
        'schedule_end_time', //get shift pagi

        'start_latitude', //absen berangkat
        'start_longitude', //absen berangkat
        'end_latitude', //absen pulang
        'end_longitude', //absen pulang

        'start_time', //berngkat
        'end_time', //pulang
        'is_wfa',
        'deskripsi'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kantor(): BelongsTo
    {
        return $this->belongsTo(Kantor::class);
    }

    public function isLate()
    {
        $scheduleStartTime = Carbon::parse($this->schedule_start_time);
        $startTime = Carbon::parse($this->start_time);

        return $startTime->greaterThan($scheduleStartTime);
    }

    public function onTimeOrLate()
    {
        $scheduleStartTime = Carbon::parse($this->schedule_start_time);
        $startTime = Carbon::parse($this->start_time);

        if ($startTime->greaterThan($scheduleStartTime)) {
            $duration = $startTime->diff($scheduleStartTime);

            $hours = $duration->h;
            $minutes = $duration->i;

            return "{$hours} jam {$minutes} menit";
        }
    }

    public function workDuration()
    {
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);

        $duration = $endTime->diff($startTime);

        $hours = $duration->h;
        $minutes = $duration->i;

        return "{$hours} jam {$minutes} menit";
    }
}
