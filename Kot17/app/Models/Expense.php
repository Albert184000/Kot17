<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category',
        'amount',
        'currency',      // ប្រភេទលុយ ($ ឬ ៛)
        'expense_date',
        'description',
        'vendor_name',
        'receipt_number',
        'receipt_image',
        'recorded_by',
        'note',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'reference');
    }
}