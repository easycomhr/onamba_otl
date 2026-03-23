<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboard;
use App\Http\Controllers\Employee\OtController;
use App\Http\Controllers\Employee\LeaveController;
use App\Http\Controllers\Employee\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Redirect root ─────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

// ── Auth ──────────────────────────────────────────────────────────────────
Route::get('/login',  [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── Employee ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {

        Route::get('/dashboard', [EmployeeDashboard::class, 'index'])->name('dashboard');

        // OT
        Route::get('/ot',           [OtController::class, 'index']) ->name('ot.index');
        Route::get('/ot/register',  [OtController::class, 'create'])->name('ot.create');
        Route::post('/ot',          [OtController::class, 'store']) ->name('ot.store');

        // Leave
        Route::get('/leave',          [LeaveController::class, 'index']) ->name('leave.index');
        Route::get('/leave/register', [LeaveController::class, 'create'])->name('leave.create');
        Route::post('/leave',         [LeaveController::class, 'store']) ->name('leave.store');

        // Profile
        Route::get('/profile',           [ProfileController::class, 'show'])          ->name('profile');
        Route::post('/change-password',  [ProfileController::class, 'changePassword'])->name('change-password');
    });