<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationReportController extends Controller
{
    public function index(Request $request)
    {
        $exchangeRate = 4100;

        $from = $request->input('from', now()->toDateString());
        $to   = $request->input('to', now()->toDateString());

        $base = Donation::query()
            ->whereDate('donated_at', '>=', $from)
            ->whereDate('donated_at', '<=', $to);

        $totalUSD = (float) (clone $base)->where('currency', 'USD')->sum('amount');
        $totalKHR = (float) (clone $base)->where('currency', 'KHR')->sum('amount');
        $totalInUSD = $totalUSD + ($totalKHR / $exchangeRate);

        $countAll = (int) (clone $base)->count();

        $topDonors = (clone $base)
            ->selectRaw('donor_name, COUNT(*) as times')
            ->groupBy('donor_name')
            ->orderByDesc('times')
            ->limit(10)
            ->get();

        $latest = (clone $base)
            ->orderByDesc('donated_at')
            ->limit(10)
            ->get();

        return view('collector.reports.donations', compact(
            'from','to',
            'exchangeRate',
            'totalUSD','totalKHR','totalInUSD',
            'countAll',
            'topDonors','latest'
        ));
    }
}
