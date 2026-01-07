<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('avatar')) {
    function avatar($user, $size = 'w-12 h-12') {
        if (!$user) return '';

        if (!empty($user->avatar) && Storage::disk('public')->exists($user->avatar)) {
            $url = asset('storage/' . $user->avatar);
            return '<img src="'.$url.'" class="'.$size.' rounded-full object-cover border-2 border-white shadow">';
        }

        $name = $user->name ?? '?';
        $initial = mb_strtoupper(mb_substr($name, 0, 1));

        return '<div class="'.$size.' rounded-full bg-gradient-to-tr from-slate-400 to-slate-600 flex items-center justify-center text-white font-black shadow">'
                .$initial.
               '</div>';
    }
}
