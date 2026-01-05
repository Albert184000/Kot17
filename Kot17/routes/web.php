<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController as AdminReport;

// Treasurer Controllers
use App\Http\Controllers\Treasurer\DashboardController as TreasurerDashboard;
use App\Http\Controllers\Treasurer\DonationController;
use App\Http\Controllers\Treasurer\ExpenseController;
use App\Http\Controllers\Treasurer\ReportController as TreasurerReport;

// Collector Controllers
use App\Http\Controllers\Collector\DashboardController as CollectorDashboard;
use App\Http\Controllers\Collector\CollectionController;

// Member Controllers
use App\Http\Controllers\Member\DashboardController as MemberDashboard;
use App\Http\Controllers\Member\ProfileController;



use Illuminate\Support\Facades\Artisan;

Route::get('/', fn () => redirect()->route('login'));

// Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

// Auth
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return match ($role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'treasurer' => redirect()->route('treasurer.dashboard'),
            'collector' => redirect()->route('collector.dashboard'),
            'member'    => redirect()->route('member.dashboard'),
            default     => abort(403),
        };
    })->name('dashboard');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('members', MemberController::class);
    Route::resource('users', UserController::class)->except(['show']);

    // ✅ reset password route (put here ONLY, no duplicate group below)
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])
        ->name('users.resetPassword');

    Route::get('/reports', [AdminReport::class, 'index'])->name('reports.index');
    Route::get('/reports/monthly', [AdminReport::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/custom', [AdminReport::class, 'custom'])->name('reports.custom');
});

// Treasurer
Route::middleware(['auth', 'role:treasurer,admin'])->prefix('treasurer')->name('treasurer.')->group(function () {
    Route::get('/dashboard', [TreasurerDashboard::class, 'index'])->name('dashboard');

    // ❌ remove duplicate expenses resource (keep only one)
    Route::resource('donations', DonationController::class)->only(['index','create','store','show']);
    Route::resource('expenses', ExpenseController::class);

    Route::get('/reports', [TreasurerReport::class, 'index'])->name('reports.index');
    Route::get('/reports/monthly', [TreasurerReport::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/custom', [TreasurerReport::class, 'custom'])->name('reports.custom');
});

// Collector
Route::middleware(['auth', 'role:collector,admin'])->prefix('collector')->name('collector.')->group(function () {
    Route::get('/dashboard', [CollectorDashboard::class, 'index'])->name('dashboard');

    Route::get('/collections/daily', [CollectionController::class, 'daily'])->name('collections.daily');
    Route::post('/collections/collect', [CollectionController::class, 'collect'])->name('collections.collect');
    Route::get('/collections/history', [CollectionController::class, 'history'])->name('collections.history');
});

// Member
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboard::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/payments', [ProfileController::class, 'payments'])->name('payments');

    // ❌ remove duplicate skip-meal route, keep ONE
    Route::post('/skip-meal', [MemberDashboard::class, 'skipMeal'])->name('skip_meal');
});
Route::get('/init-db', function () {
    try {
        Artisan::call('migrate --force');
        return "✅ Database Initialized Successfully!";
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});