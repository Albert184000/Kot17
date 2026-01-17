<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\Member;
use App\Models\User;
use App\Models\Debtor; // កុំភ្លេច use Model ថ្មីនេះ
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- ១. គណនាសមតុល្យសាច់ប្រាក់ក្នុងដៃ (Cash In Hand) ---
        // ដុល្លារ ($)
        $totalDonationUSD = Donation::where('currency', 'USD')->sum('amount');
        $totalExpenseUSD = Expense::where('currency', 'USD')->sum('amount') ?? 0;
        $cashInHandUSD = $totalDonationUSD - $totalExpenseUSD;

        // រៀល (៛)
        $totalDonationKHR = Donation::where('currency', 'KHR')->sum('amount');
        $totalExpenseKHR = Expense::where('currency', 'KHR')->sum('amount') ?? 0;
        $cashInHandKHR = $totalDonationKHR - $totalExpenseKHR;

        // --- ២. ទាញទិន្នន័យអ្នកជំពាក់ (Debtors) ---
        $debtors = Debtor::where('status', 'unpaid')->latest()->get();
        $totalDebtUSD = $debtors->where('currency', 'USD')->sum('debt_amount');
        $totalDebtKHR = $debtors->where('currency', 'KHR')->sum('debt_amount');

        // --- ៣. ទាញទិន្នន័យទូទៅ ---
        $totalMembers = Member::count();
        
        // ចំណូលខែនេះ (USD) សម្រាប់បង្ហាញក្នុងក្រាហ្វតូចៗ
        $monthlyIncomeUSD = Donation::where('currency', 'USD')
            ->whereMonth('donated_at', now()->month)
            ->whereYear('donated_at', now()->year)
            ->sum('amount');

        // ចំណូលចុងក្រោយ (Recent)
        $recentDonations = Donation::with('member')
            ->latest('donated_at')
            ->take(5)
            ->get();

        // --- ៤. បញ្ជូនទិន្នន័យទៅ View ---
        return view('treasurer.dashboard', compact(
            'cashInHandUSD', 
            'cashInHandKHR', 
            'totalDebtUSD', 
            'totalDebtKHR', 
            'totalMembers', 
            'recentDonations',
            'debtors',
            'monthlyIncomeUSD'
        ));
    }
}