<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;
    protected $table = 'profiles';

    protected $guarded = [];

    protected $fillable = [
        'user_id',
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

        'province_id',
        'city_id',
        'district_id',
        'subdistrict_id',

        'ijasah_terakhir',
        'divisi_id',
        'jabatan_id',
        'tanggal_masuk',
        'kantor_id',
        'foto_ktp',
        'signature',
        'profile_pic',
        'is_completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Satu profile milik satu divisi
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    // Satu profile milik satu jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }
}
