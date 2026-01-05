<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

   public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $credentials['is_active'] = true;

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    // Use 'error' here to match the variable in your blade view
    return back()->with('error', 'អ៊ីមែល ឬ លេខសម្ងាត់ មិនត្រឹមត្រូវ');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
