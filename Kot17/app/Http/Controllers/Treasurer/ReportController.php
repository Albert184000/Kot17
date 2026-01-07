<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
  public function index(Request $request)
{
    // ១. កំណត់ថ្ងៃខែសម្រាប់ Filter (ត្រូវដាក់នៅខាងលើគេដើម្បីឱ្យមាន $startDate ប្រើ)
    $filterDate = $request->input('filter_date', now()->format('Y-m'));
    $year = date('Y', strtotime($filterDate));
    $month = date('m', strtotime($filterDate));
    
    // បង្កើត variable សម្រាប់ប្រើក្នុង Query
    $startDate = "$year-$month-01 00:00:00";
    $endDate = date('Y-m-t', strtotime($startDate)) . " 23:59:59";

    // ២. គណនាចំណូល (Donations) បំបែកតាមរូបិយប័ណ្ណ
    $totalDonationsUSD = \App\Models\Donation::whereBetween('donated_at', [$startDate, $endDate])
                            ->where('currency', 'USD')->sum('amount');
    $totalDonationsKHR = \App\Models\Donation::whereBetween('donated_at', [$startDate, $endDate])
                            ->where('currency', 'KHR')->sum('amount');

    // ៣. គណនាចំណាយ (Expenses) បំបែកតាមរូបិយប័ណ្ណ
    $totalExpensesUSD = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])
                            ->where('currency', 'USD')->sum('amount');
    $totalExpensesKHR = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])
                            ->where('currency', 'KHR')->sum('amount');

    // ៤. គណនាចំណាយម្ហូប (ស្វែងរកពាក្យថា "ម្ហូប")
    $foodExpensesUSD = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])
                        ->where('description', 'like', '%ម្ហូប%')
                        ->where('currency', 'USD')->sum('amount');
    $foodExpensesKHR = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])
                        ->where('description', 'like', '%ម្ហូប%')
                        ->where('currency', 'KHR')->sum('amount');

    // ៥. គណនាសមតុល្យសល់សុទ្ធ
    $balanceUSD = $totalDonationsUSD - $totalExpensesUSD;
    $balanceKHR = $totalDonationsKHR - $totalExpensesKHR;

    // ៦. ទាញទិន្នន័យសម្រាប់តារាងលម្អិត (Union ចំណូល និង ចំណាយ)
    $incomes = \App\Models\Donation::whereBetween('donated_at', [$startDate, $endDate])
        ->select('id', 'amount', 'currency', 'donated_at as date', 'donor_name as name', \Illuminate\Support\Facades\DB::raw("'income' as type"));

    $reportData = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])
        ->select('id', 'amount', 'currency', 'created_at as date', 'description as name', \Illuminate\Support\Facades\DB::raw("'expense' as type"))
        ->union($incomes)
        ->orderBy('date', 'desc')
        ->get();

    // ៧. បោះទិន្នន័យទៅកាន់ View
    return view('treasurer.reports.index', compact(
        'year', 'month', 
        'totalDonationsUSD', 'totalDonationsKHR', 
        'totalExpensesUSD', 'totalExpensesKHR',
        'foodExpensesUSD', 'foodExpensesKHR', 
        'balanceUSD', 'balanceKHR', 
        'reportData'
    ));
}
}
