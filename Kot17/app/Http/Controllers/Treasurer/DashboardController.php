<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Member;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
{
    // ១. ទាញទិន្នន័យសម្រាប់ Cards
    $totalDonation = \App\Models\Donation::sum('amount');
    $totalExpense = \App\Models\Expense::sum('amount') ?? 0;
    $balance = $totalDonation - $totalExpense;
    $totalMembers = \App\Models\Member::count();
    $totalUsers = \App\Models\User::count();

    // ២. ទាញចំណូលប្រចាំខែ (អថេរដែលកំពុង Error)
    $monthlyIncome = \App\Models\Donation::whereMonth('donated_at', now()->month)
        ->whereYear('donated_at', now()->year)
        ->sum('amount');

    // ៣. ទាញបញ្ជីចុងក្រោយ
    $recentDonations = \App\Models\Donation::with('member')
        ->latest('donated_at')
        ->take(5)
        ->get();

    // ៤. បោះទៅកាន់ View (ត្រូវប្រាកដថាមាន 'monthlyIncome' ក្នុង compact)
    return view('treasurer.dashboard', compact(
        'totalDonation', 
        'totalExpense', 
        'balance', 
        'totalMembers', 
        'totalUsers', 
        'recentDonations',
        'monthlyIncome' 
    ));
}
}