<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debtor extends Model
{
    protected $fillable = [
        'name', 
        'phone', 
        'debt_amount', 
        'currency', 
        'reason', 
        'status'
    ];
}