<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\NotificationChannelController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StatusPageController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ────────────────────────────────────────────────────────────

Route::get('/', function () {
    return view('welcome');
});

// Status page publik (tanpa login)
Route::get('/status/{slug}', [StatusPageController::class, 'show'])->name('status.public');

// ─── Authenticated Routes ─────────────────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [TargetController::class, 'index'])->name('dashboard');

    // Target Management
    Route::prefix('targets')->name('targets.')->group(function () {
        Route::post('/',                [TargetController::class, 'store'])->name('store');
        Route::get('/{id}',             [TargetController::class, 'show'])->name('show');
        Route::get('/{id}/edit',        [TargetController::class, 'edit'])->name('edit');
        Route::patch('/{id}',           [TargetController::class, 'update'])->name('update');
        Route::delete('/{id}',          [TargetController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/pause',      [TargetController::class, 'togglePause'])->name('pause');
    });

    // Notification Channels
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',              [NotificationChannelController::class, 'index'])->name('index');
        Route::post('/',             [NotificationChannelController::class, 'store'])->name('store');
        Route::post('/telegram/lookup', [NotificationChannelController::class, 'lookupTelegramChats'])->name('telegram.lookup');
        Route::delete('/{id}',       [NotificationChannelController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle',  [NotificationChannelController::class, 'toggleActive'])->name('toggle');
        Route::post('/{id}/test',    [NotificationChannelController::class, 'test'])->name('test');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',          [ReportController::class, 'index'])->name('index');
        Route::get('/export-csv',[ReportController::class, 'exportCsv'])->name('export-csv');
    });

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
