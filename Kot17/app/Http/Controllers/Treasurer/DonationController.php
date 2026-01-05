<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::with('member')->latest()->paginate(10);
        return view('treasurer.donations.index', compact('donations'));
    }               
    
    public function create()
    {
        $members = Member::all(); 
        return view('treasurer.donations.create', compact('members'));
    }

    public function store(Request $request)
{
    // ១. Validation
    $request->validate([
        'member_id'  => 'nullable|exists:members,id',
        'donor_name' => 'required_if:member_id,null|string|max:255',
        'amount'     => 'required|numeric|min:0',
        'currency'   => 'required|in:USD,KHR',
        'donated_at' => 'required|date', // ត្រូវតែមានថ្ងៃខែ
    ]);

    // ២. រក្សាទុកទិន្នន័យ (ពិនិត្យមើលឈ្មោះ key ឱ្យច្បាស់)
    Donation::create([
        'member_id'  => $request->member_id,
        'donor_name' => $request->donor_name,
        'amount'     => $request->amount,
        'currency'   => $request->currency,
        'donated_at' => $request->donated_at, // <--- ប្រាកដថាបន្ទាត់នេះមាន
        'note'       => $request->note,
        'user_id'    => auth()->id(),
    ]);

    return redirect()->route('treasurer.donations.index')
                     ->with('success', '✅ បានរក្សាទុកការប្រគេនបច្ច័យរួចរាល់!');
}
}