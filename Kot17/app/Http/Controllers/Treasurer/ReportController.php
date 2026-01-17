<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Expense;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getReportData($request);
        return view('treasurer.reports.index', $data);
    }

    public function print(Request $request)
    {
        $data = $this->getReportData($request);
        return view('treasurer.reports.print', $data);
    }

    public function exportExcel(Request $request) 
    {
        $data = $this->getReportData($request);
        $fileName = "Report_" . $data['filterDate'] . ".xls";
        return response()->view('treasurer.reports.export_table', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=$fileName");
    }

    public function exportDocx(Request $request) 
    {
        $data = $this->getReportData($request);
        $fileName = "Report_" . $data['filterDate'] . ".doc";
        return response()->view('treasurer.reports.export_table', $data)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', "attachment; filename=$fileName");
    }

    private function getReportData(Request $request)
    {
        $filterDate = $request->input('filter_date', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($filterDate . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // សមតុល្យដើមគ្រា
        $opIncomeUSD = Donation::where('donated_at', '<', $startDate)->where('currency', 'USD')->sum('amount');
        $opIncomeKHR = Donation::where('donated_at', '<', $startDate)->where('currency', 'KHR')->sum('amount');
        $opExpenseUSD = Expense::where('created_at', '<', $startDate)->where('currency', 'USD')->sum('amount');
        $opExpenseKHR = Expense::where('created_at', '<', $startDate)->where('currency', 'KHR')->sum('amount');

        $initialUSD = $opIncomeUSD - $opExpenseUSD;
        $initialKHR = $opIncomeKHR - $opExpenseKHR;

        $runningUSD = $initialUSD;
        $runningKHR = $initialKHR;

        $dailyReport = [];
        for ($d = 1; $d <= $startDate->daysInMonth; $d++) {
            $currentDate = $startDate->copy()->day($d)->format('Y-m-d');
            $inUSD = Donation::whereDate('donated_at', $currentDate)->where('currency', 'USD')->sum('amount');
            $inKHR = Donation::whereDate('donated_at', $currentDate)->where('currency', 'KHR')->sum('amount');
            $outUSD = Expense::whereDate('created_at', $currentDate)->where('currency', 'USD')->sum('amount');
            $outKHR = Expense::whereDate('created_at', $currentDate)->where('currency', 'KHR')->sum('amount');

            $runningUSD += ($inUSD - $outUSD);
            $runningKHR += ($inKHR - $outKHR);

            $dailyReport[] = [
                'day' => $d,
                'date' => $currentDate,
                'in_usd' => $inUSD, 'in_khr' => $inKHR,
                'out_usd' => $outUSD, 'out_khr' => $outKHR,
                'bal_usd' => $runningUSD, 'bal_khr' => $runningKHR
            ];
        }

        $totalInUSD = Donation::whereBetween('donated_at', [$startDate, $endDate])->where('currency', 'USD')->sum('amount');
        $totalInKHR = Donation::whereBetween('donated_at', [$startDate, $endDate])->where('currency', 'KHR')->sum('amount');
        $totalOutUSD = Expense::whereBetween('created_at', [$startDate, $endDate])->where('currency', 'USD')->sum('amount');
        $totalOutKHR = Expense::whereBetween('created_at', [$startDate, $endDate])->where('currency', 'KHR')->sum('amount');

        return [
            'dailyReport' => $dailyReport, 
            'year' => $startDate->format('Y'), 
            'month' => $startDate->format('m'), 
            'filterDate' => $filterDate,
            'totalDonationsUSD' => $totalInUSD, 
            'totalDonationsKHR' => $totalInKHR,
            'totalExpensesUSD' => $totalOutUSD, 
            'totalExpensesKHR' => $totalOutKHR,
            'monthNetKHR' => $totalInKHR - $totalOutKHR,
            'monthNetUSD' => $totalInUSD - $totalOutUSD,
            'runningUSD' => $runningUSD, 
            'runningKHR' => $runningKHR,
        ];
    }
}