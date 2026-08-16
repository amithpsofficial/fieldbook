<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\LabourExpenseController;
use App\Http\Controllers\FertilizerApplicationController;
use App\Http\Controllers\OtherExpenseController;
use App\Http\Controllers\CropStockController;
use App\Http\Controllers\CropSaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/farm', [LandController::class, 'index'])->name('farm');
    Route::resource('lands', LandController::class)->except(['index']);

    Route::resource('labour-expenses', LabourExpenseController::class);
    Route::resource('fertilizer-applications', FertilizerApplicationController::class);
    Route::resource('other-expenses', OtherExpenseController::class);
    Route::resource('crop-stocks', CropStockController::class);
    Route::resource('crop-sales', CropSaleController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';