<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('avatar')) {
    function avatar($user, $size = 'w-12 h-12') {
    if (!$user) return '';

    $avatar = $user->avatar;
    if (!empty($avatar)) {
        // បើឈ្មោះរូបភាពអត់មានជាប់ពាក្យ avatars/ ទេ យើងថែមឱ្យវា
        $path = str_starts_with($avatar, 'avatars/') ? $avatar : 'avatars/' . $avatar;
        
        if (Storage::disk('public')->exists($path)) {
            $url = asset('storage/' . $path);
            return '<img src="'.$url.'" class="'.$size.' rounded-full object-cover border-2 border-white shadow-sm">';
        }
    }

    // Placeholder បើអត់មានរូប
    $name = $user->name ?? '?';
    $initial = mb_strtoupper(mb_substr($name, 0, 1));
    return '<div class="'.$size.' rounded-full bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center text-white font-black shadow-inner">'.$initial.'</div>';
}
}