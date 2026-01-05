<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Donation;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $today = \Carbon\Carbon::today()->toDateString();

    // ១. ទាញទិន្នន័យសរុប
    $totalMembers = Member::count();
    $totalDonations = Donation::sum('amount');
    $totalUsers = User::count();
    $totalExpenses = 0; 

    // ២. រាប់ចំនួនអ្នកឆាន់ (ដាក់ឈ្មោះអថេរឱ្យត្រូវជាមួយ View)
    $todayAttendance = Attendance::where('attendance_date', $today)
                        ->where('is_present', true)
                        ->count();
    
    // បង្កើតអថេរមួយទៀតដែលមានតម្លៃដូចគ្នា ដើម្បីការពារ Error $presentCount
    $presentCount = $todayAttendance;

    // ៣. ទាញសកម្មភាពបច្ច័យ ៥ ចុងក្រោយ
    $recentDonations = Donation::with('member')->latest()->take(5)->get();

    // ៤. បញ្ជូនទៅ View (ដាក់ឈ្មោះទាំងពីរចូលក្នុង compact)
    return view('admin.dashboard', compact(
        'totalMembers', 
        'totalDonations', 
        'totalExpenses', 
        'totalUsers',
        'recentDonations',
        'todayAttendance',
        'presentCount' // <--- បន្ថែមឈ្មោះនេះចូល
    ));
}
}