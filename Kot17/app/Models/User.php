<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'role',
    'is_active',
    'user_id',
    'avatar', // Make sure this is included
];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function collections()
    {
        return $this->hasMany(DailyCollection::class, 'collected_by');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'created_by');
    }

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTreasurer()
    {
        return $this->role === 'treasurer';
    }

    public function isCollector()
    {
        return $this->role === 'collector';
    }

    public function isMember()
    {
        return $this->role === 'member';
    }
}