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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
