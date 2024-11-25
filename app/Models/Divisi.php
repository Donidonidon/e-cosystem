<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'divisis';

    public function profile()
    {
        return $this->hasMany(Profile::class);
    }

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class);
    }
}
