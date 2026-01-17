<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Expense;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    private function resolveDates(Request $request): array
    {
        $type = $request->input('report_type', 'month');
        $filterDate = $request->input('filter_date', now()->format('Y-m'));

        try {
            $base = Carbon::createFromFormat('Y-m', $filterDate)->startOfMonth();
        } catch (\Throwable $e) {
            $base = now()->startOfMonth();
        }

        $year = (int) $base->year;
        $month = (int) $base->month;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($type === 'custom' && $startDate && $endDate) {
            return ['custom', $year, $month, $startDate, $endDate];
        }

        if ($type === 'year') {
            $startDate = Carbon::create($year, 1, 1)->toDateString();
            $endDate = Carbon::create($year, 12, 31)->toDateString();
            return [$type, $year, $month, $startDate, $endDate];
        }

        if ($type === 'semester') {
            $semStartMonth = ($month <= 6) ? 1 : 7;
            $semEndMonth = ($month <= 6) ? 6 : 12;
            $startDate = Carbon::create($year, $semStartMonth, 1)->toDateString();
            $endDate = Carbon::create($year, $semEndMonth, 1)->endOfMonth()->toDateString();
            return [$type, $year, $month, $startDate, $endDate];
        }

        // default month
        $startDate = $base->toDateString();
        $endDate = $base->copy()->endOfMonth()->toDateString();
        return ['month', $year, $month, $startDate, $endDate];
    }

    private function buildReportData(string $startDate, string $endDate)
    {
        $incomes = Donation::whereBetween('donated_at', [$startDate, $endDate])
            ->get(['id', 'amount', 'donated_at as date', 'donor_name as name'])
            ->map(fn($r) => ['id'=>$r->id,'amount'=>$r->amount,'date'=>$r->date,'name'=>$r->name,'type'=>'income']);

        $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->get(['id', 'amount', 'created_at as date', 'description as name'])
            ->map(fn($r) => ['id'=>$r->id,'amount'=>$r->amount,'date'=>$r->date,'name'=>$r->name,'type'=>'expense']);

        return collect($incomes)->merge($expenses)->sortByDesc('date')->values();
    }

    private function reportLabel(string $type, int $year, int $month): string
    {
        if ($type === 'year') return "ប្រចាំឆ្នាំ $year";
        if ($type === 'semester') return "ប្រចាំឆមាស " . (($month <= 6) ? 1 : 2) . " ឆ្នាំ $year";
        if ($type === 'custom') return "កំណត់ខ្លួនឯង";
        return "ប្រចាំខែ $month ឆ្នាំ $year";
    }

    public function index(Request $request)
    {
        [$type, $year, $month, $startDate, $endDate] = $this->resolveDates($request);

        $totalIn  = (float) Donation::whereBetween('donated_at', [$startDate, $endDate])->sum('amount');
        $totalOut = (float) Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $profit   = $totalIn - $totalOut;

        $label = $this->reportLabel($type, $year, $month);

        $reportData = $this->buildReportData($startDate, $endDate);

        // Delta %
        $days = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $prevEnd = Carbon::parse($startDate)->subDay()->toDateString();
        $prevStart = Carbon::parse($startDate)->subDays($days)->toDateString();

        $prevIn = (float) Donation::whereBetween('donated_at', [$prevStart, $prevEnd])->sum('amount');
        $prevOut = (float) Expense::whereBetween('created_at', [$prevStart, $prevEnd])->sum('amount');

        $deltaIn  = $prevIn > 0 ? (($totalIn - $prevIn) / $prevIn) * 100 : ($totalIn > 0 ? 100 : 0);
        $deltaOut = $prevOut > 0 ? (($totalOut - $prevOut) / $prevOut) * 100 : ($totalOut > 0 ? 100 : 0);

        // Donut chart
        $topExpense = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("COALESCE(NULLIF(TRIM(description),''),'Other') as label, SUM(amount) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $grandExpense = (float) $topExpense->sum('total');
        $top3 = $topExpense->take(3);
        $otherSum = (float) $topExpense->slice(3)->sum('total');

        $donutLabels = $top3->pluck('label')->values()->all();
        $donutValues = $top3->pluck('total')->map(fn($v) => (float)$v)->values()->all();

        if($otherSum>0){
            $donutLabels[]='Other';
            $donutValues[]=$otherSum;
        }
        if(count($donutLabels)==0){
            $donutLabels=['No Expense'];
            $donutValues=[1];
        }

        // Audience / monthly charts
        $period = Carbon::parse($startDate)->daysUntil(Carbon::parse($endDate));
        $dailyIncomes = Donation::whereBetween('donated_at', [$startDate,$endDate])
            ->selectRaw("DATE(donated_at) as date, SUM(amount) as total")
            ->groupBy('date')->pluck('total','date')->toArray();
        $dailyExpenses = Expense::whereBetween('created_at', [$startDate,$endDate])
            ->selectRaw("DATE(created_at) as date, SUM(amount) as total")
            ->groupBy('date')->pluck('total','date')->toArray();

        $audienceLabels = [];
        $audienceBars = [];
        $audienceLine = [];
        foreach($period as $d){
            $ds = $d->toDateString();
            $audienceLabels[] = $d->format('d/m');
            $audienceBars[] = (float)($dailyIncomes[$ds]??0);
            $audienceLine[] = (float)($dailyExpenses[$ds]??0);
        }

        return view('admin.reports.index', compact(
            'type','label','startDate','endDate','year','month',
            'totalIn','totalOut','profit','reportData',
            'audienceLabels','audienceBars','audienceLine',
            'donutLabels','donutValues',
            'deltaIn','deltaOut'
        ));
    }

    public function print(Request $request)
    {
        [$type, $year, $month, $startDate, $endDate] = $this->resolveDates($request);

        $totalIn  = (float) Donation::whereBetween('donated_at', [$startDate, $endDate])->sum('amount');
        $totalOut = (float) Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $profit   = $totalIn - $totalOut;

        $reportData = $this->buildReportData($startDate, $endDate);
        $label = $this->reportLabel($type, $year, $month);

        return view('admin.reports.print', compact(
            'type','label','startDate','endDate','year','month',
            'totalIn','totalOut','profit','reportData'
        ));
    }
}
