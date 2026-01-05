<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // បន្ថែម Method នេះសម្រាប់ Error ដែលបងកំពុងជួប
    public function show()
    {
        $user = auth()->user();
        return view('member.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('member.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'ព័ត៌មានត្រូវបានធ្វើបច្ចុប្បន្នភាព!');
    }

    public function payments()
    {
        // ប្រសិនបើបងមាន Model Donation
        // $payments = \App\Models\Donation::where('user_id', auth()->id())->get();
        return view('member.profile.payments');
    }
}