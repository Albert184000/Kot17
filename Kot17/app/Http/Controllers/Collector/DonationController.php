<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $q = Donation::query()->with('user');

        if ($request->filled('from')) {
            $q->whereDate('donated_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('donated_at', '<=', $request->to);
        }

        if ($request->filled('currency')) {
            $q->where('currency', $request->currency);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($qq) use ($s) {
                $qq->where('donor_name', 'like', "%{$s}%")
                   ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $donations = $q->orderByDesc('donated_at')
            ->paginate(20)
            ->withQueryString();

        // ✅ Your view exists: resources/views/collector/collections/index.blade.php
        return view('collector.collections.index', compact('donations'));
    }
}
