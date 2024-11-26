<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $guarded = [];

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

        'province_id',
        'city_id',
        'district_id',
        'subdistrict_id',

        'ijasah_terakhir',
        'divisi_id',
        'jabatan_id',
        'tanggal_masuk',
        'kantor',
        'foto_ktp',
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

    public function kantor(): HasOne
    {
        return $this->hasOne(Kantor::class);
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            // Pastikan nik tidak null dan hilangkan spasi
            if ($model->nik) {
                $model->nik = str_replace(' ', '', $model->nik);
            }
        });
    }
}
