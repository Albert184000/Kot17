<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'user_id',
        'member_id',
        'donor_name',
        'donor_phone',
        'amount',
        'donation_date',
        'purpose',
        'notes',
        'recorded_by',
        'currency',
        'donated_at',
        'description',

        // ✅ add these 2
            'reason',
            'status',
    ];

    protected $casts = [
        'donation_date' => 'date',
        'amount' => 'decimal:2',
        'donated_at' => 'datetime',
    ];

    // ✅ Collector owner (who created it)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    // ✅ Admin/Treasurer who recorded (if you use recorded_by)
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'reference');
    }

    public function getDonorDisplayNameAttribute()
    {
        if ($this->member) {
            return $this->member->name;
        }
        return $this->donor_name ?? 'អ្នកបរិច្ចាគទូទៅ';
    }

    
}
