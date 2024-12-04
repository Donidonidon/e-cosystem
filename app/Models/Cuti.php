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

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
