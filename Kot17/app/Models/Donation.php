<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    // រួមបញ្ចូលរាល់ Field ទាំងអស់ដែលបងប្រើប្រាស់
    protected $fillable = [
        'user_id', // បន្ថែមអាហ្នឹងចូល
        'member_id',
        'donor_name',
        'donor_phone',
        'amount',
        'donation_date', // បើបងប្រើ donated_at កែវាឱ្យត្រូវជាមួយ Database
        'purpose',
        'notes',
        'recorded_by',
        'currency', // បន្ថែមត្រង់នេះ
        'donated_at',
    ];

    // កំណត់ប្រភេទទិន្នន័យឱ្យត្រឹមត្រូវ (ងាយស្រួលប្រើពេលហៅចេញក្នុង Blade)
    protected $casts = [
    'donation_date' => 'date', // ឬ 'datetime'
    'amount' => 'decimal:2',
    'donated_at' => 'datetime',
    ];

    /**
     * Relationship ទៅកាន់សមាជិក (Member)
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * Relationship ទៅកាន់ Admin អ្នកកត់ត្រា (Recorded By)
     */
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Relationship សម្រាប់ប្រព័ន្ធបញ្ជីថវិកា (Transactions)
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'reference');
    }

    /**
     * Helper សម្រាប់បង្ហាញឈ្មោះអ្នកបរិច្ចាគ (សមាជិក ឬ អ្នកក្រៅ)
     */
    public function getDonorDisplayNameAttribute()
    {
        if ($this->member) {
            return $this->member->name;
        }
        return $this->donor_name ?? 'អ្នកបរិច្ចាគទូទៅ';
    }
}