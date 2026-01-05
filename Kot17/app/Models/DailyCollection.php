<?php
// app/Models/DailyCollection.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyCollection extends Model
{
    protected $fillable = [
        'member_id',
        'collected_by',
        'collection_date',
        'amount',
        'status',
        'collected_at_time',
        'notes',
    ];

    protected $casts = [
        'collection_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('collection_date', today());
    }

    public function scopeCollected($query)
    {
        return $query->where('status', 'collected');
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('collection_date', $date);
    }
}