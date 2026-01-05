<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
   public function index(Request $request)
{
    // ១. កំណត់ថ្ងៃខែ និងបង្កើតអថេរ $year, $month ដែលបាត់
    $filterDate = $request->input('filter_date', now()->format('Y-m'));
    $year = date('Y', strtotime($filterDate));
    $month = date('m', strtotime($filterDate));

    $startDate = $request->input('start_date', "$year-$month-01");
    $endDate = $request->input('end_date', date('Y-m-t', strtotime($startDate)));

    // ២. ទាញទិន្នន័យសរុប
    $totalIn = \App\Models\Donation::whereBetween('donated_at', [$startDate, $endDate])->sum('amount');
    $totalOut = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
    $profit = $totalIn - $totalOut;

    // ៣. ទាញទិន្នន័យសម្រាប់តារាង (Union)
    $incomes = \App\Models\Donation::whereBetween('donated_at', [$startDate, $endDate])
        ->select('id', 'amount', 'donated_at as date', 'donor_name as name', \Illuminate\Support\Facades\DB::raw("'income' as type"));

    $reportData = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])
        ->select('id', 'amount', 'created_at as date', 'description as name', \Illuminate\Support\Facades\DB::raw("'expense' as type"))
        ->union($incomes)
        ->orderBy('date', 'desc')
        ->get();

    // ៤. បញ្ជូនអថេរទៅ View (ត្រូវប្រាកដថាមាន $year និង $month ក្នុង compact)
    return view('admin.reports.index', compact(
        'startDate', 'endDate', 'year', 'month', 
        'totalIn', 'totalOut', 'profit', 'reportData'
    ));
}
}