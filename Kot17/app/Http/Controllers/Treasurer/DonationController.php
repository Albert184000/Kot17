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
        $request->validate([
            'member_id'  => 'nullable|exists:members,id',
            'donor_name' => 'nullable|string|max:255',
            'amount'     => 'required|numeric|min:0',
            'currency'   => 'required|in:USD,KHR',
            'donated_at' => 'required|date',
        ]);

        Donation::create([
            'member_id'  => $request->member_id,
            'donor_name' => $request->donor_name,
            'amount'     => $request->amount,
            'currency'   => $request->currency,
            'donated_at' => $request->donated_at,
            'note'       => $request->note,
            'user_id'    => auth()->id(),
        ]);

        return redirect()->route('treasurer.donations.index')->with('success', '✅ រក្សាទុកបានជោគជ័យ!');
    }

    public function edit(Donation $donation)
    {
        $members = Member::all();
        return view('treasurer.donations.edit', compact('donation', 'members'));
    }

    public function update(Request $request, Donation $donation)
{
    $request->validate([
        'member_id'  => 'nullable|exists:members,id',
        'donor_name' => 'nullable|string|max:255',
        'amount'     => 'required|numeric|min:0',
        'currency'   => 'required|in:USD,KHR',
        'donated_at' => 'required|date',
    ]);

    $donation->update([
        'member_id'  => $request->member_id,
        'donor_name' => $request->donor_name,
        'amount'     => $request->amount,
        'currency'   => $request->currency,
        'donated_at' => $request->donated_at,
        'note'       => $request->note,
    ]);

    return redirect()->route('treasurer.donations.index')->with('success', '✅ កែប្រែបានជោគជ័យ!');
}

    public function destroy(Donation $donation)
    {
        $donation->delete();
        return back()->with('success', '🗑️ លុបទិន្នន័យរួចរាល់!');
    }
}