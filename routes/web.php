<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\IncidentTypeController;
use App\Http\Controllers\LangController;
use Illuminate\Support\Facades\Route;

// Auth routes (guests only)
Route::get('/login',           [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',          [AuthController::class, 'login']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot.password');
Route::post('/forgot-password',[AuthController::class, 'forgotPassword']);
Route::get('/reset-password',  [AuthController::class, 'showResetPassword'])->name('reset.password');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Language switch (works without auth)
Route::get('/lang/{code}', [LangController::class, 'set'])->name('lang.set');

// Authenticated routes
Route::middleware('auth.custom')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Incidents
    Route::get('/incidents',                        [IncidentController::class, 'index'])->name('incidents.index');
    Route::get('/incidents/create',                 [IncidentController::class, 'create'])->name('incidents.create');
    Route::post('/incidents/create',                [IncidentController::class, 'store'])->name('incidents.store');
    Route::get('/incidents/{id}',                   [IncidentController::class, 'view'])->name('incidents.view');
    Route::get('/incidents/{id}/notice/{type}',     [IncidentController::class, 'downloadNotice'])->name('incidents.notice');
    Route::get('/incidents/{id}/edit',              [IncidentController::class, 'edit'])->name('incidents.edit');
    Route::post('/incidents/{id}/edit',             [IncidentController::class, 'update'])->name('incidents.update');
    Route::post('/incidents/{id}/delete',           [IncidentController::class, 'delete'])->name('incidents.delete');

    // Reports
    Route::get('/reports',  [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports', [ReportController::class, 'generate'])->name('reports.generate');

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        Route::get('/users',              [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',       [UserController::class, 'create'])->name('users.create');
        Route::post('/users/create',      [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit',    [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/{id}/edit',   [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{id}/delete', [UserController::class, 'delete'])->name('users.delete');

        Route::get('/incident-types',               [IncidentTypeController::class, 'index'])->name('incident-types.index');
        Route::post('/incident-types/create',       [IncidentTypeController::class, 'store'])->name('incident-types.store');
        Route::post('/incident-types/{id}/delete',  [IncidentTypeController::class, 'destroy'])->name('incident-types.destroy');

        Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    });
});
