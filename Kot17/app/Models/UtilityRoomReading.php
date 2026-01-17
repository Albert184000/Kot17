<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtilityRoomReading extends Model
{
    protected $fillable = [
        'utility_bill_id',
        'room_id','room_name','meter_no',
        'old_reading','new_reading','usage_units',
        'common_share_units','total_units',
        'amount_before_donation','donation_share','amount_final',
        'paid_amount','balance_amount',
        'status','note',
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(UtilityBill::class, 'utility_bill_id');
    }
}
