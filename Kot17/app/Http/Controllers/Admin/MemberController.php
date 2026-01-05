<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * បង្ហាញបញ្ជីសមាជិកទាំងអស់
     */
    public function index()
    {
        // ទាញយកសមាជិកទាំងអស់ រួមជាមួយព័ត៌មាន Admin អ្នកបង្កើត (Eager Loading)
        $members = Member::with('creator')->latest()->get(); 
        return view('admin.members.index', compact('members'));
    }

    /**
     * បង្ហាញ Form បង្កើតសមាជិក
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * រក្សាទុកទិន្នន័យសមាជិកថ្មីចូល Database
     */
    public function store(Request $request)
    {
        // 1. ត្រួតពិនិត្យទិន្នន័យ
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        // 2. បង្កើតលេខកូដសមាជិកអូតូ (ឧទាហរណ៍: MB-0001)
        $lastMember = Member::latest('id')->first();
        $nextId = $lastMember ? $lastMember->id + 1 : 1;
        $memberCode = 'MB-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        // 3. រក្សាទុកទិន្នន័យ
        Member::create([
            'member_code' => $memberCode,
            'name'        => $request->name,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'user_id'     => auth()->id(), // រក្សាទុក ID របស់ Admin ដែលកំពុង Login
            'status'      => 'active',    // តម្លៃដើមគឺ Active
            'join_date'   => now(),       // ថ្ងៃចូលគឺថ្ងៃចុះឈ្មោះ
        ]);

        return redirect()->route('admin.members.index')
                         ->with('success', 'ចុះឈ្មោះសមាជិកថ្មីជោគជ័យ!');
    }

    /**
     * បង្ហាញ Form កែប្រែព័ត៌មានសមាជិក
     */
    public function edit(Member $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    /**
     * ធ្វើបច្ចុប្បន្នភាពទិន្នន័យ (Update)
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string',
            'status'  => 'required|in:active,inactive',
        ]);

        $member->update($request->all());

        return redirect()->route('admin.members.index')
                         ->with('success', 'កែប្រែព័ត៌មានសមាជិកជោគជ័យ!');
    }

    /**
     * លុបទិន្នន័យសមាជិក
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return back()->with('success', 'លុបសមាជិកចេញពីប្រព័ន្ធជោគជ័យ!');
    }
}