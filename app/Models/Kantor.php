<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kantor extends Model
{
    use SoftDeletes;
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
