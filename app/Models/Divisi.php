<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Divisi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'divisis';

    protected $fillable = [
        'name'
    ];

    public function profile()
    {
        return $this->hasMany(Profile::class);
    }

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class);
    }
}
