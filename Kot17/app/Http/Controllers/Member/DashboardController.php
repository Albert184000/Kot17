<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $today = today();

        /**
         * 1️⃣ My report (today, amount = 0) => OFFLINE REPORT
         */
        $myReport = Donation::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('amount', 0)
            ->latest()
            ->first();

        /**
         * 2️⃣ Organization roles
         */
        $admin      = User::where('role', 'admin')->first();
        $treasurer  = User::where('role', 'treasurer')->first();
        $collectors = User::where('role', 'collector')->orderBy('name')->get();

        /**
         * ✅ 3️⃣ Members list (IMPORTANT)
         * - old code: role=member only ❌ (miss monks/officers)
         * - new code: everyone that belongs to org (adjust if you want)
         */
        $members = User::query()
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        /**
         * ✅ 4️⃣ Monk groups (for dashboard org-chart)
         * Use person_type + monk_rank (NOT role)
         */
        $mahaTheras = User::whereNull('deleted_at')
            ->where('person_type', 'monk')
            ->where('monk_rank', 'maha_thera')
            ->orderByDesc('vassa')
            ->orderBy('name')
            ->get();

        $seniorMonks = User::whereNull('deleted_at')
            ->where('person_type', 'monk')
            ->whereIn('monk_rank', ['bhikkhu', 'senior_monk']) // support old values
            ->orderByDesc('vassa')
            ->orderBy('name')
            ->get();

        $juniors = User::whereNull('deleted_at')
            ->where('person_type', 'monk')
            ->whereIn('monk_rank', ['samanera', 'junior_monk', 'monk', null]) // support old values + null
            ->orderByDesc('vassa')
            ->orderBy('name')
            ->get();

        /**
         * ✅ 5️⃣ Students (adjust rule)
         * If you store students in role=student:
         */
        $students = User::whereNull('deleted_at')
            ->where('role', 'student')
            ->orderBy('name')
            ->get();

        /**
         * 6️⃣ Donation stats (for current user)
         */
        $totalIn = Donation::where('user_id', $user->id)->sum('amount');
        $paymentsCount = Donation::where('user_id', $user->id)->count();

        $recentDonations = Donation::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        /**
         * ✅ 7️⃣ Attendance group (ALL people you want to count)
         * - old code used role monk/student ❌
         * - new code uses all members (same as org)
         */
        $allMembers = User::query()
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        $totalPeople = $allMembers->count();

        /**
         * ✅ 8️⃣ Today offline reports (ONE QUERY)
         */
        $todayOfflineReports = Donation::whereDate('created_at', $today)
            ->where('amount', 0)
            ->select(['id','user_id','reason','status','created_at'])
            ->get()
            ->keyBy('user_id');

        $offlineUserIds = $todayOfflineReports->keys()->values()->all();
        $offlineCount   = $todayOfflineReports->count();
        $onlineCount    = max(0, $totalPeople - $offlineCount);

        return view('member.dashboard', compact(
            'user',
            'myReport',

            'admin',
            'treasurer',
            'collectors',
            'members',

            // ✅ monk groups for org chart
            'mahaTheras',
            'seniorMonks',
            'juniors',

            'students',

            'totalIn',
            'paymentsCount',
            'recentDonations',

            'allMembers',
            'totalPeople',
            'offlineCount',
            'onlineCount',
            'todayOfflineReports',
            'offlineUserIds'
        ));
    }

    // skipMeal(), sendTelegramNotification(), cancelSkip() keep your code (OK)
}
