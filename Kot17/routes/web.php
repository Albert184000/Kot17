<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

// Auth
use App\Http\Controllers\Auth\LoginController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\UtilitiesController;

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

    // Central dashboard redirect by role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return match ($role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'treasurer' => redirect()->route('treasurer.dashboard'),
            'collector' => redirect()->route('collector.dashboard'),
            'member'    => redirect()->route('member.dashboard'),
            'utility'   => redirect()->route('admin.utilities.index'), // ✅ utility redirect
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

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // User Management (Soft Deletes must be ABOVE Resource)
        Route::get('users/trash', [UserController::class, 'trash'])->name('users.trash');
        Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
        Route::resource('users', UserController::class)->except(['show']);

        // Members
        Route::resource('members', MemberController::class);

        // Reports
        Route::get('/reports', [AdminReport::class, 'index'])->name('reports.index');
        Route::get('/reports/monthly', [AdminReport::class, 'monthly'])->name('reports.monthly');
        Route::get('/reports/custom', [AdminReport::class, 'custom'])->name('reports.custom');
    });

/*
|--------------------------------------------------------------------------
| 4) Utilities Routes (admin + utility)
|--------------------------------------------------------------------------
| IMPORTANT: utilities must NOT be inside admin-only group,
| otherwise role utility cannot access it.
*/
Route::middleware(['auth', 'role:admin,utility'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/utilities', [UtilitiesController::class, 'index'])->name('utilities.index');

        // If later you add CRUD:
        // Route::get('/utilities/create', [UtilitiesController::class, 'create'])->name('utilities.create');
        // Route::post('/utilities', [UtilitiesController::class, 'store'])->name('utilities.store');
        // Route::get('/utilities/{id}/edit', [UtilitiesController::class, 'edit'])->name('utilities.edit');
        // Route::put('/utilities/{id}', [UtilitiesController::class, 'update'])->name('utilities.update');
        // Route::delete('/utilities/{id}', [UtilitiesController::class, 'destroy'])->name('utilities.destroy');
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
        Route::resource('donations', TreasurerDonationController::class)->only(['index','create','store','show']);
        Route::resource('expenses', ExpenseController::class);

        Route::get('/reports', [TreasurerReport::class, 'index'])->name('reports.index');
        Route::get('/reports/monthly', [TreasurerReport::class, 'monthly'])->name('reports.monthly');
        Route::get('/reports/custom', [TreasurerReport::class, 'custom'])->name('reports.custom');
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

        // collections/donations
        Route::post('/donations', [CollectionController::class, 'store'])->name('donations.store');
        Route::post('/collections/collect', [CollectionController::class, 'store'])->name('collections.collect');

        Route::get('/donations', [CollectorDonationController::class, 'index'])->name('donations.index');

        // reports
        Route::get('/reports/lunch', [CollectionController::class, 'lunchReport'])->name('reports.lunch');
        Route::get('/reports/donations', [CollectorDonationReportController::class, 'index'])->name('reports.donations');

        // summary / history
        Route::post('/daily-summary', [CollectorDashboard::class, 'sendDailySummary'])->name('summary');
        Route::get('/collections/daily', [CollectionController::class, 'daily'])->name('collections.daily');
        Route::get('/collections/history', [CollectionController::class, 'history'])->name('collections.history');
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

        if (!$token || !$channelId) {
            return "❌ Error: សូមពិនិត្យមើល .env";
        }

        $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $channelId,
            'text' => "<b>🔔 ការជូនដំណឹងពីប្រព័ន្ធ KOT17</b>\n\nប្រព័ន្ធត្រូវបានភ្ជាប់ជោគជ័យ!",
            'parse_mode' => 'HTML'
        ]);

        return $response->json();
    });
});
