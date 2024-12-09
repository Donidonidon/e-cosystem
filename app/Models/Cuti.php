<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuti extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'status',
        'approved_by_hrd',
        'approved_by_leader',
        'approved_by_direksi',
        'notes',
        'approval_by_hrd_id',
        'approval_by_direksi_id',
        'jumlah_hari',
        'path_cuti_pdf',
        'approval_by_leader_id',

    ];

    protected static function booted()
    {
        // Event untuk mendeteksi perubahan data pada cuti
        static::updated(function (Cuti $cuti) {
            // Jika status berubah menjadi approved
            if ($cuti->status === 'approved') {
                $user = User::find($cuti->user_id); // Ambil data user dari user_id

                if ($user) {
                    // Kurangi jatah cuti user
                    $user->jatah_cuti -= $cuti->jumlahHari;

                    // Pastikan jatah cuti tidak negatif
                    if ($user->jatah_cuti < 0) {
                        $user->jatah_cuti = 0;
                    }

                    $user->save(); // Simpan perubahan
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
