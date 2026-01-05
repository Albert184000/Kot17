<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

  public function store(Request $request)
{
    $data = $request->validate([
        'name' => ['required','string','max:255'],
        'email' => ['required','email','max:255','unique:users,email'],
        'phone' => ['nullable','string','max:30'],
        'role' => ['required', \Illuminate\Validation\Rule::in(['admin','treasurer','collector','member'])],
        'is_active' => ['nullable','boolean'],
        'password' => ['required','string','min:8','confirmed'],
    ]);

    $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
    $data['is_active'] = $request->boolean('is_active');

    \App\Models\User::create($data);

    return redirect()->route('admin.users.index')->with('success', 'បង្កើតអ្នកប្រើប្រាស់បានជោគជ័យ!');
}

    // ✅ THIS WAS MISSING
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'phone' => ['nullable','string','max:30'],
            'role' => ['required', Rule::in(['admin','treasurer','collector','member'])],
            'password' => ['nullable','string','min:6','confirmed'],
            'is_active' => ['nullable','boolean'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'កែប្រែអ្នកប្រើប្រាស់បានជោគជ័យ!');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'មិនអាចលុបគណនីរបស់ខ្លួនឯងបានទេ!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'លុបអ្នកប្រើប្រាស់បានជោគជ័យ!');
    }

    public function resetPassword(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'មិនគួរ reset password របស់ខ្លួនឯងពីទីនេះទេ!');
        }

        $newPassword = '12345678';

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return back()->with('success', "Reset password បានជោគជ័យ! Password ថ្មី: {$newPassword}");
    }
}
