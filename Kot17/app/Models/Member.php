<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'user_id',
        'member_code',
        'name',       // 🔥 ត្រូវតែថែម ដើម្បី Save ឈ្មោះ
        'phone',      // 🔥 ត្រូវតែថែម ដើម្បី Save លេខទូរស័ព្ទ
        'room_number',
        'status',
        'join_date',
        'leave_date',
        'address',
        'emergency_contact',
        'daily_rate',
    ];

    protected $casts = [
        'join_date' => 'date',
        'leave_date' => 'date',
        'daily_rate' => 'decimal:2',
    ];

    // Relationship ទៅកាន់ Admin អ្នកបង្កើត (ប្រើបានទាំង user() និង creator())
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dailyCollections() {
        return $this->hasMany(DailyCollection::class);
    }

    // Scopes & Helpers
    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    public function isActive() {
        return $this->status === 'active';
    }

    public function hasPaidToday() {
        return $this->dailyCollections()
            ->whereDate('collection_date', today())
            ->where('status', 'collected')
            ->exists();
    }

    public function sendToTelegram($id)
{
    $member = Member::with('user')->findOrFail($id);
    
    $token = "YOUR_BOT_TOKEN_HERE"; // ដាក់ Token របស់ Bot អ្នក
    $chatId = "YOUR_CHAT_ID_HERE";   // ដាក់ Chat ID របស់ Group អ្នក

    $text = "🔔 *រាយការណ៍ស្ថានភាពសមាជិក*\n"
          . "----------------------------\n"
          . "👤 ឈ្មោះ៖ " . $member->user->name . "\n"
          . "🆔 លេខកូដ៖ " . $member->member_code . "\n"
          . "📍 បន្ទប់៖ " . ($member->room_number ?? 'មិនទាន់កំណត់') . "\n"
          . "📅 ថ្ងៃខែ៖ " . now()->format('d-m-Y H:i');

    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($text) . "&parse_mode=Markdown";

    // ប្រើ file_get_contents ឬ Http Client របស់ Laravel
    file_get_contents($url);

    return back()->with('success', 'បានផ្ញើដំណឹងទៅ Telegram រួចរាល់!');
}
}