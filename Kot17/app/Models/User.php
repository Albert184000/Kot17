<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes; // ១. បន្ថែមជួរនេះ
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage; // បន្ថែមនេះដើម្បីកុំឱ្យ Error ត្រង់ avatarUrl
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SkipMeal;

class User extends Authenticatable
{


use SoftDeletes; // ២. បន្ថែមជួរនេះដែរ
protected $dates = ['deleted_at']; // សម្រាប់ Laravel កំណែចាស់ៗខ្លះ


    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'person_type',
        'monk_rank',
        'vassa',
        'avatar',
        'is_active',
        'user_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // បន្ថែមសម្រាប់ Laravel 10/11+
    ];

    // ==========================================
    // Relationships
    // ==========================================

    /**
     * តភ្ជាប់ទៅកាន់ Table Reports (ឆែកវត្តមានថ្ងៃនេះ)
     * ប្រើសម្រាប់បង្ហាញ Status "នៅកុដិ" ឬ "មិននៅកុដិ" តាម Real Data
     */
    // ... កូដខាងលើរក្សាទុកដដែល
public function todayAttendance(): HasOne
{
    return $this->hasOne(Attendance::class, 'user_id')->whereDate('created_at', now());
}


// ... កូដខាងក្រោមរក្សាទុកដដែល

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function collections(): HasMany
    {
        return $this->hasMany(DailyCollection::class, 'collected_by');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'created_by');
    }

    // ==========================================
    // Role Checking Methods
    // ==========================================

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isTreasurer(): bool { return $this->role === 'treasurer'; }
    public function isCollector(): bool { return $this->role === 'collector'; }
    public function isMember(): bool { return $this->role === 'member'; }

    // ==========================================
    // Helpers
    // ==========================================

    public function avatarUrl()
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }
        // បើគ្មានរូបភាព ប្រើ UI Avatars
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random&color=fff';
    }

    public function initial()
    {
        return mb_strtoupper(mb_substr($this->name ?? '?', 0, 1));
    }
}