<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $fillable = [
        'users_id',
        'first_name',
        'last_name',
        'email',
        'nik',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',

        'alamat',
        'provinces_id',
        'regencies_id',
        'districts_id',

        'ijasah_terakhir',
        'jabatan_sekarang',
        'tanggal_masuk',
        'kantor',
        'foto_ktp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
