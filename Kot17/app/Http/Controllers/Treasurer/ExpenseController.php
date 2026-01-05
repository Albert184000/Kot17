<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index()
    {
        // ប្រើ paginate(15) ដើម្បីកុំឱ្យតារាងវែងពេក
        $expenses = Expense::orderBy('expense_date', 'desc')->paginate(15);
        
        // អត្រាប្តូរប្រាក់ (អាចដាក់ក្នុង .env ឬទាញពី DB)
        $exchange_rate = 4100; 

        return view('treasurer.expenses.index', compact('expenses', 'exchange_rate'));
    }

    public function create()
    {
        $categories = [
            'food' => 'ម្ហូបអាហារ/ម្ហូបព្រឹក',
            'utility' => 'ទឹក-ភ្លើង',
            'maintenance' => 'ជួសជុល/ថែទាំ',
            'charity' => 'សប្បុរសធម៌',
            'other' => 'ផ្សេងៗ'
        ];
        return view('treasurer.expenses.create', compact('categories'));
    }

  public function store(Request $request)
{
    // ១. Validation (បន្ថែម currency ទៅក្នុងបញ្ជី)
    $request->validate([
        'expense_date' => 'required|date',
        'description'  => 'required|string|max:255',
        'category'     => 'required|string',
        'amount'       => 'required|numeric|min:0',
        'currency'     => 'required|in:USD,KHR', // បន្ថែមការត្រួតពិនិត្យប្រភេទលុយ
    ]);

    // ២. រក្សាទុកទិន្នន័យ
    \App\Models\Expense::create([
        'expense_date' => $request->expense_date,
        'description'  => $request->description,
        'category'     => $request->category,
        'amount'       => $request->amount,
        'currency'     => $request->currency, // ចាប់យកតម្លៃពី Select Box ($ ឬ ៛)
        'recorded_by'  => auth()->id(),
    ]);

    return redirect()->route('treasurer.expenses.index')
                     ->with('success', '✅ បានបញ្ចូលការចំណាយរួចរាល់!');
}
public function destroy($id)
{
    $expense = \App\Models\Expense::findOrFail($id);
    $expense->delete();

    return redirect()->route('treasurer.expenses.index')
                     ->with('success', '🗑️ បានលុបទិន្នន័យចំណាយរួចរាល់!');
}


// បង្ហាញ Form សម្រាប់កែប្រែទិន្នន័យ
public function edit($id)
{
    $expense = Expense::findOrFail($id);
    $categories = [
        'food' => 'ម្ហូបអាហារ/ម្ហូបព្រឹក',
        'utility' => 'ទឹក-ភ្លើង',
        'maintenance' => 'ជួសជុល/ថែទាំ',
        'charity' => 'សប្បុរសធម៌',
        'other' => 'ផ្សេងៗ'
    ];
    
    return view('treasurer.expenses.edit', compact('expense', 'categories'));
}

// រក្សាទុកទិន្នន័យដែលបានកែប្រែ
public function update(Request $request, $id)
{
    $request->validate([
        'expense_date' => 'required|date',
        'description'  => 'required|string|max:255',
        'category'     => 'required|string',
        'amount'       => 'required|numeric|min:0',
        'currency'     => 'required|in:USD,KHR',
    ]);

    $expense = Expense::findOrFail($id);
    $expense->update([
        'expense_date' => $request->expense_date,
        'description'  => $request->description,
        'category'     => $request->category,
        'amount'       => $request->amount,
        'currency'     => $request->currency,
        'note'         => $request->note,
    ]);

    return redirect()->route('treasurer.expenses.index')
                     ->with('success', '✅ បានធ្វើបច្ចុប្បន្នភាពទិន្នន័យរួចរាល់!');
}
}