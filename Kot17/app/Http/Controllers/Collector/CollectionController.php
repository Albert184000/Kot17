<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function daily()
    {
        return view('collector.collections.daily');
    }

    public function history()
    {
        return view('collector.collections.history');
    }
public function lunchReport()
{
    // 1. Get the base query for Lunch
    $query = \App\Models\Donation::where(function($q) {
        $q->where('description', 'LIKE', '%ចង្ហាន់%')
          ->orWhere('description', 'LIKE', '%Lunch%');
    });

    // 2. Calculate Totals
    $totalUSD = (clone $query)->where('currency', 'USD')->sum('amount');
    $totalKHR = (clone $query)->where('currency', 'KHR')->sum('amount');

    // 3. Get Paginated Data
    $donations = $query->latest()->paginate(15);

    return view('collector.reports.lunch', compact('donations', 'totalUSD', 'totalKHR'));
}
    // ✅ ONE Save Endpoint
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_name'  => ['required', 'string', 'max:255'],
            'currency'    => ['required', 'in:USD,KHR'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        Donation::create([
            'user_id'     => auth()->id(),
            'donor_name'  => $validated['donor_name'],
            'currency'    => $validated['currency'],
            'amount'      => $validated['amount'],
            'description' => $validated['description'],
            'donated_at'  => now(), // ✅ fix donated_at
        ]);

        return back()->with('success', '✅ រក្សាទុកបានជោគជ័យ');
    }
}
