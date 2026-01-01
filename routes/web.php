<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthDataController;
use App\Http\Controllers\DataQualityController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    
    // Health Data Management
    Route::resource('health-data', HealthDataController::class)->parameters([
        'health-data' => 'healthData'
    ]);
    
    // Data Quality & Governance
    Route::prefix('data-quality')->name('data-quality.')->group(function () {
        Route::get('/', [DataQualityController::class, 'index'])->name('index');
        Route::post('/validate', [DataQualityController::class, 'runValidation'])->name('validate');
        Route::get('/report', [DataQualityController::class, 'report'])->name('report');
    });
    
    // Security & User Management
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/', [SecurityController::class, 'index'])->name('index');
        Route::get('/audit-trail', [SecurityController::class, 'auditTrail'])->name('audit-trail');
        Route::get('/risk-analysis', [SecurityController::class, 'riskAnalysis'])->name('risk-analysis');
        Route::get('/users', [SecurityController::class, 'users'])->name('users');
    });
    
    // User Management
    Route::resource('users', UserController::class);
});
