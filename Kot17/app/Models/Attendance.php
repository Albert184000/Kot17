<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'status', // ឧទាហរណ៍៖ 'away' ឬ 'present'
        'reason',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}