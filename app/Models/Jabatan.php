<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jabatan extends Model
{
    use HasFactory;

    protected $fillable = ['divisi_id', 'jabatan'];

    // Satu jabatan milik satu divisi
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }
}
