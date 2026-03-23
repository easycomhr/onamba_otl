<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OtApprovalController;
use App\Http\Controllers\Admin\LeaveApprovalController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\OtImportController;
use App\Http\Controllers\Admin\LeaveImportController;
use App\Http\Controllers\Admin\OtReportController;
use App\Http\Controllers\Admin\LeaveReportController;
use App\Http\Controllers\Admin\OtRegisterController;
use App\Http\Controllers\Admin\LeaveRegisterController;

/*
|--------------------------------------------------------------------------
| Admin (Manager) Routes
| Prefix  : /admin
| Name    : admin.*
| Middleware: web, auth, role:manager  (role middleware added inside group)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:manager'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── OT Approvals ─────────────────────────────────────────────────────
    Route::prefix('approvals/ot')->name('approvals.ot.')->group(function () {
        Route::get('/',          [OtApprovalController::class, 'index'])  ->name('index');
        Route::get('/{id}',      [OtApprovalController::class, 'show'])   ->name('show');
        Route::post('/{id}/approve', [OtApprovalController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject',  [OtApprovalController::class, 'reject']) ->name('reject');
    });

    // ── Leave Approvals ──────────────────────────────────────────────────
    Route::prefix('approvals/leave')->name('approvals.leave.')->group(function () {
        Route::get('/',          [LeaveApprovalController::class, 'index'])  ->name('index');
        Route::get('/{id}',      [LeaveApprovalController::class, 'show'])   ->name('show');
        Route::post('/{id}/approve', [LeaveApprovalController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject',  [LeaveApprovalController::class, 'reject']) ->name('reject');
    });

    // ── Register OT / Leave on behalf of employee ────────────────────────
    Route::prefix('register')->name('register.')->group(function () {
        Route::get('/ot',        [OtRegisterController::class,    'create'])->name('ot.create');
        Route::get('/ot/employees', [OtRegisterController::class, 'employees'])->name('ot.employees');
        Route::post('/ot',       [OtRegisterController::class,    'store']) ->name('ot.store');
        Route::get('/leave',     [LeaveRegisterController::class, 'create'])->name('leave.create');
        Route::get('/leave/employees', [LeaveRegisterController::class, 'employees'])->name('leave.employees');
        Route::post('/leave',    [LeaveRegisterController::class, 'store']) ->name('leave.store');
    });

    // ── Employee Management ───────────────────────────────────────────────
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/',          [EmployeeController::class, 'index'])  ->name('index');
        Route::get('/{id}/edit', [EmployeeController::class, 'edit'])   ->name('edit');
        Route::put('/{id}',      [EmployeeController::class, 'update']) ->name('update');
        Route::post('/{id}/change-password', [EmployeeController::class, 'changePassword'])->name('change-password');
    });

    // ── Bulk Import ───────────────────────────────────────────────────────
    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/ot',        [OtImportController::class,    'index'])->name('ot');
        Route::get('/ot/template', [OtImportController::class,  'template'])->name('ot.template');
        Route::post('/ot',       [OtImportController::class,    'store'])->name('ot.store');
        Route::get('/leave',     [LeaveImportController::class, 'index'])->name('leave');
        Route::get('/leave/template', [LeaveImportController::class, 'template'])->name('leave.template');
        Route::post('/leave',    [LeaveImportController::class, 'store'])->name('leave.store');
    });

    // ── Export Reports ────────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/ot',        [OtReportController::class,    'index'])->name('ot');
        Route::post('/ot/export',[OtReportController::class,    'export'])->name('ot.export');
        Route::get('/leave',     [LeaveReportController::class, 'index'])->name('leave');
        Route::post('/leave/export', [LeaveReportController::class, 'export'])->name('leave.export');
    });
});
