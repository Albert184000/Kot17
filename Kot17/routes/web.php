<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

// Auth
use App\Http\Controllers\Auth\LoginController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\UtilitiesController; // ✅ Keep ONE controller only

// Treasurer Controllers
use App\Http\Controllers\Treasurer\DashboardController as TreasurerDashboard;
use App\Http\Controllers\Treasurer\DonationController as TreasurerDonationController;
use App\Http\Controllers\Treasurer\ExpenseController;
use App\Http\Controllers\Treasurer\ReportController as TreasurerReport;

// Collector Controllers
use App\Http\Controllers\Collector\DashboardController as CollectorDashboard;
use App\Http\Controllers\Collector\CollectionController;
use App\Http\Controllers\Collector\DonationController as CollectorDonationController;
use App\Http\Controllers\Collector\DonationReportController as CollectorDonationReportController;

// Member Controllers
use App\Http\Controllers\Member\DashboardController as MemberDashboard;
use App\Http\Controllers\Member\ProfileController;

/*
|--------------------------------------------------------------------------
| 1) Public & Guest Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| 2) Auth Shared Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return match ($role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'treasurer' => redirect()->route('treasurer.dashboard'),
            'collector' => redirect()->route('collector.dashboard'),
            'member'    => redirect()->route('member.dashboard'),
            'utility'   => redirect()->route('admin.utilities.index'),
            default     => abort(403),
        };
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| 3) Admin Routes (admin only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Users
        Route::get('users/trash', [UserController::class, 'trash'])->name('users.trash');
        Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
        Route::resource('users', UserController::class)->except(['show']);

        // Members
        Route::resource('members', MemberController::class);

        // Reports
        Route::get('/reports', [AdminReport::class, 'index'])->name('reports.index');
        Route::get('/reports/print', [AdminReport::class, 'print'])->name('reports.print');
    });

/*
|--------------------------------------------------------------------------
| 4) Utilities Routes (admin + utility)  ✅ single source of truth
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,utility'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/utilities', [UtilitiesController::class, 'index'])->name('utilities.index');
        Route::post('/utilities', [UtilitiesController::class, 'store'])->name('utilities.store');

        Route::get('/utilities/{bill}', [UtilitiesController::class, 'show'])
            ->whereNumber('bill')
            ->name('utilities.show');

        Route::put('/utilities/{bill}', [UtilitiesController::class, 'update'])
            ->whereNumber('bill')
            ->name('utilities.update');

        // ✅ added (from your duplicate block)
        Route::get('/utilities/{bill}/export', [UtilitiesController::class, 'export'])
            ->whereNumber('bill')
            ->name('utilities.export');

        Route::get('/utilities/{bill}/print', [UtilitiesController::class, 'print'])
            ->whereNumber('bill')
            ->name('utilities.print');
    });

/*
|--------------------------------------------------------------------------
| 5) Treasurer Routes (treasurer + admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:treasurer,admin'])
    ->prefix('treasurer')
    ->name('treasurer.')
    ->group(function () {

        Route::get('/dashboard', [TreasurerDashboard::class, 'index'])->name('dashboard');

        Route::resource('donations', TreasurerDonationController::class);
        Route::resource('expenses', ExpenseController::class);

        Route::get('/reports', [TreasurerReport::class, 'index'])->name('reports.index');
        Route::get('/reports/print', [TreasurerReport::class, 'print'])->name('reports.print');
        Route::get('/reports/export/excel', [TreasurerReport::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('/reports/export/docx', [TreasurerReport::class, 'exportDocx'])->name('reports.export.docx');
    });

/*
|--------------------------------------------------------------------------
| 6) Collector Routes (collector + admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:collector,admin'])
    ->prefix('collector')
    ->name('collector.')
    ->group(function () {

        Route::get('/dashboard', [CollectorDashboard::class, 'index'])->name('dashboard');

        Route::post('/donations', [CollectionController::class, 'store'])->name('donations.store');
        Route::post('/collections/collect', [CollectionController::class, 'store'])->name('collections.collect');
        Route::get('/donations', [CollectorDonationController::class, 'index'])->name('donations.index');

        Route::get('/reports/lunch', [CollectionController::class, 'lunchReport'])->name('reports.lunch');
        Route::get('/reports/donations', [CollectorDonationReportController::class, 'index'])->name('reports.donations');

        Route::post('/daily-summary', [CollectorDashboard::class, 'sendDailySummary'])->name('summary');
        Route::get('/collections/daily', [CollectionController::class, 'daily'])->name('collections.daily');
        Route::get('/collections/history', [CollectionController::class, 'history'])->name('collections.history');

        Route::post('/expenses/store', [CollectionController::class, 'storeExpense'])->name('expenses.store');
    });

/*
|--------------------------------------------------------------------------
| 7) Member Routes (member only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:member'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {

        Route::get('/dashboard', [MemberDashboard::class, 'index'])->name('dashboard');

        Route::post('/skip-meal', [MemberDashboard::class, 'skipMeal'])->name('skip_meal');
        Route::delete('/cancel-skip/{id}', [MemberDashboard::class, 'cancelSkip'])->name('cancel_skip');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/payments', [ProfileController::class, 'payments'])->name('payments');
    });

/*
|--------------------------------------------------------------------------
| 8) System & Telegram Utilities (admin only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/init-db', function () {
        Artisan::call('migrate --force');
        return "✅ Database Initialized Successfully!";
    });

    Route::get('/send-to-channel', function () {
        $token = env('TELEGRAM_BOT_TOKEN');
        $channelId = env('TELEGRAM_CHANEL_ID');

        if (!$token || !$channelId) return "❌ Error: សូមពិនិត្យមើល .env";

        $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $channelId,
            'text' => "<b>🔔 ការជូនដំណឹងពីប្រព័ន្ធ KOT17</b>\n\nប្រព័ន្ធត្រូវបានភ្ជាប់ជោគជ័យ!",
            'parse_mode' => 'HTML'
        ]);

        return $response->json();
    });
});

/*
|--------------------------------------------------------------------------
| 9) Forgot Password + Reset Password
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', fn () => view('auth.forgot-password'))->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', 'បានផ្ញើតំណភ្ជាប់ Reset ទៅ Email រួចរាល់ ✅')
        : back()->withErrors(['email' => 'មិនអាចផ្ញើបាន។ សូមពិនិត្យ Email ឲ្យត្រឹមត្រូវ']);
})->name('password.email');

Route::get('/reset-password/{token}', function (string $token, Request $request) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->query('email'),
    ]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email','password','password_confirmation','token'),
        function ($user) use ($request) {
            $user->forceFill(['password' => Hash::make($request->password)])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'បានកំណត់លេខសម្ងាត់ថ្មីរួចរាល់ ✅')
        : back()->withErrors(['email' => 'Reset token មិនត្រឹមត្រូវ ឬផុតកំណត់']);
})->name('password.update');
