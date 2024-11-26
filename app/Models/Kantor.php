<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    protected $table = 'kantors';

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius'
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
