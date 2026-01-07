<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Donation;
use App\Models\Member;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
{
    $today = Carbon::today()->toDateString();
    $users = User::with('todayAttendance')->where('is_active', true)->get();

    $totalMembers   = Member::count();
    $totalDonations = Donation::sum('amount');
    $totalUsers     = $users->count();
    $totalExpenses  = 0;

    // ⚠️ this line in your code is weird, but I keep it as-is
    $presentCount = $users->where('todayAttendance', null)->count();

    // ✅ Utility: support new role "utility" + fallback old "utilities_treasurer"
    $utilities = $users->whereIn('role', ['utility', 'utilities_treasurer'])->sortBy('name');

    // ✅ stats cards
    $stats = [
        'total_novices' => $users->whereIn('monk_rank', ['junior_monk', 'monk'])->count(),
        'utilities'     => $utilities->count(),
    ];

    // --- Org Chart groups ---
    $admin = $users->where('role', 'admin')->first();

    $mahaTheras = $users->where('person_type', 'monk')
        ->where('monk_rank', 'maha_thera')
        ->sortByDesc('vassa');

    $seniorMonks = $users->where('person_type', 'monk')
        ->where('monk_rank', 'senior_monk')
        ->sortByDesc('vassa');

    $monks = $users->where('person_type', 'monk')
        ->whereIn('monk_rank', ['junior_monk', 'monk'])
        ->sortByDesc('vassa');

    $treasurer  = $users->where('role', 'treasurer')->first();
    $collectors = $users->where('role', 'collector')->sortBy('name');
    $students   = $users->where('person_type', 'lay')->where('role', 'member')->sortBy('name');

    return view('admin.dashboard', [
        'users'           => $users,
        'stats'           => $stats,
        'totalMembers'    => $totalMembers,
        'totalDonations'  => $totalDonations,
        'totalExpenses'   => $totalExpenses,
        'totalUsers'      => $totalUsers,
        'presentCount'    => $presentCount,
        'recentDonations' => Donation::with('user')->latest()->take(5)->get(),

        'admin'       => $admin,
        'mahaTheras'  => $mahaTheras,
        'seniorMonks' => $seniorMonks,
        'monks'       => $monks,

        // ✅ 3 officers
        'treasurer'  => $treasurer,
        'utilities'  => $utilities,      // ⭐ NEW (use this in blade)
        'collectors' => $collectors,

        // ✅ students below all
        'students'   => $students,

        // ✅ Backward compatible (if your blade still uses this name)
        'utilitiesTreasurers' => $utilities,
    ]);
}

}