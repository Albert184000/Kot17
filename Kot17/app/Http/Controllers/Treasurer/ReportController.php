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
    // ១. កំណត់ថ្ងៃខែសម្រាប់ Filter
    $filterDate = $request->input('filter_date', now()->format('Y-m'));
    $year = date('Y', strtotime($filterDate));
    $month = date('m', strtotime($filterDate));
    $startDate = "$year-$month-01";
    $endDate = date('Y-m-t', strtotime($startDate));

    // ២. គណនាទឹកប្រាក់សរុប (ប្រើឈ្មោះឱ្យត្រូវតាម Blade)
    $totalDonations = \App\Models\Donation::whereBetween('donated_at', [$startDate, $endDate])->sum('amount');
    $totalExpenses = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
    
    // សន្មត់ថា foodExpenses ជាផ្នែកមួយនៃ totalExpenses (បងអាចប្ដូរ logic តាមក្រោយ)
    $foodExpenses = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])
                        ->where('description', 'like', '%ម្ហូប%')
                        ->sum('amount');

    $balance = $totalDonations - $totalExpenses;

    // ៣. ទាញទិន្នន័យសម្រាប់តារាង (Union Income & Expense)
    $incomes = \App\Models\Donation::whereBetween('donated_at', [$startDate, $endDate])
        ->select('id', 'amount', 'donated_at as date', 'donor_name as name', \Illuminate\Support\Facades\DB::raw("'income' as type"));

    $reportData = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])
        ->select('id', 'amount', 'created_at as date', 'description as name', \Illuminate\Support\Facades\DB::raw("'expense' as type"))
        ->union($incomes)
        ->orderBy('date', 'desc')
        ->get();

    return view('treasurer.reports.index', compact(
        'year', 'month', 'totalDonations', 'totalExpenses', 
        'foodExpenses', 'balance', 'reportData'
    ));
}
}
