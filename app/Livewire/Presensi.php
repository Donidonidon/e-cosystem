<?php

namespace App\Livewire;

use App\Models\Attendence;
use App\Models\Kantor;
use App\Models\Profile;
use Livewire\Component;
use App\Models\Schedule;
use App\Models\Shift;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Presensi extends Component
{
    public $latitude;
    public $longitude;
    public $insideRadius = false;
    public $timeNow;

    public $isWfa = false;
    public $deskripsi;
    public $kantorID;
    public $kantorName = '';

    // Pesan validasi khusus (opsional)
    protected $messages = [
        'deskripsi.required_if' => 'Deskripsi harus diisi yaa',
        'deskripsi.min' => 'Deskripsi harus memiliki minimal 3 karakter.',
    ];

    public function render()
    {

        $schedule = Schedule::where('user_id', Auth::user()->id)->first();
        $kantor = Kantor::all(); //selain kantor tugas

        $attendance = Attendence::where('user_id', Auth::user()->id)
            ->whereDate('created_at', date('Y-m-d'))
            ->first();
        return view('livewire.presensi', [
            'kantor' => $kantor,

            'schedule' => $schedule,
            'insideRadius' => $this->insideRadius,
            'attendance' => $attendance,
            'is_wfa' => $this->isWfa,
            'deskripsi' => $this->deskripsi,
            'kantor_id' => $this->kantorID,
            'kantor_name' => $this->kantorName
        ]);
    }

    //store data presensi
    public function store()
    {
        $this->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'kantorID' => 'required',
        ]);

        $schedule = Schedule::where('user_id', Auth::user()->id)->first();

        if ($schedule) {
            $attendance = Attendence::where('user_id', Auth::user()->id)
                ->whereDate('created_at', date('Y-m-d'))
                ->first();

            if (!$attendance) {
                Attendence::create([
                    'user_id' => Auth::user()->id,
                    'schedule_latitude' => $schedule->kantor->latitude,
                    'schedule_longitude' => $schedule->kantor->longitude,
                    'schedule_start_time' => $schedule->shift->start_time,
                    'schedule_end_time' => $schedule->shift->end_time,
                    'start_latitude' => $this->latitude,
                    'start_longitude' => $this->longitude,
                    'start_time' => Carbon::now('Asia/Jakarta')->toTimeString(),
                    'is_wfa' => $this->isWfa,
                    'deskripsi' => $this->deskripsi,
                    'kantor_id' => $this->kantorID
                ]);
            } else {
                $timeNow = Carbon::now('Asia/Jakarta');

                if ($timeNow->greaterThan($schedule->shift->end_time)) {
                    $attendance->update([
                        'end_latitude' => $this->latitude,
                        'end_longitude' => $this->longitude,
                        'end_time' => Carbon::now('Asia/Jakarta')->toTimeString(),
                    ]);
                }
            }

            return redirect()->route('filament.internal.resources.attendences.index');

            // return redirect()->route('presensi', [
            //     'schedule' => $schedule,
            //     'insideRadius' => false,
            // ]);
        }
    }
}
