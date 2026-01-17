<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Expense; // ✅ បន្ថែម Model Expense
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * ✅ បង្ហាញទំព័រចុះបញ្ជីប្រចាំថ្ងៃ (មានទាំងចំណូល និងចំណាយ)
     */
    public function daily()
    {
        $date = today();
        
        // ១. ទាញបញ្ជីចំណូលថ្ងៃនេះ
        $recentCollections = Donation::whereDate('created_at', $date)
            ->latest()
            ->get();
        
        // ២. ទាញបញ្ជីចំណាយថ្ងៃនេះ
        $recentExpenses = Expense::whereDate('created_at', $date)
            ->latest()
            ->get();

        return view('collector.collections.daily', compact('recentCollections', 'recentExpenses'));
    }

    /**
     * ✅ រក្សាទុកទិន្នន័យចំណូល (IN)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_name'  => ['required', 'string', 'max:255'],
            'currency'    => ['required', 'in:USD,KHR'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Donation::create([
            'user_id'     => auth()->id(),
            'donor_name'  => $validated['donor_name'],
            'currency'    => $validated['currency'],
            'amount'      => $validated['amount'],
            'description' => $validated['description'],
            'donated_at'  => now(), 
        ]);

        return back()->with('success', '✅ រក្សាទុកចំណូលបានជោគជ័យ');
    }

    /**
     * ✅ រក្សាទុកទិន្នន័យចំណាយ (OUT) 
     * កុំភ្លេចបង្កើត Route: Route::post('/expenses', [CollectionController::class, 'storeExpense'])->name('collector.expenses.store');
     */
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'currency' => ['required', 'in:USD,KHR'],
            'amount'   => ['required', 'numeric', 'min:0'],
        ]);

        Expense::create([
            'user_id'  => auth()->id(),
            'title'    => $validated['title'],
            'currency' => $validated['currency'],
            'amount'   => $validated['amount'],
            'date'     => now(),
        ]);

        return back()->with('success', '✅ រក្សាទុកចំណាយបានជោគជ័យ');
    }

    public function history()
    {
        $donations = Donation::latest()->paginate(20);
        return view('collector.collections.history', compact('donations'));
    }

    public function lunchReport()
    {
        $query = Donation::where(function($q) {
            $q->where('description', 'LIKE', '%ចង្ហាន់%')
              ->orWhere('description', 'LIKE', '%Lunch%');
        });

        $totalUSD = (clone $query)->where('currency', 'USD')->sum('amount');
        $totalKHR = (clone $query)->where('currency', 'KHR')->sum('amount');

        $donations = $query->latest()->paginate(15);

        return view('collector.reports.lunch', compact('donations', 'totalUSD', 'totalKHR'));
    }
}