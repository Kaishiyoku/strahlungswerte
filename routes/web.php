<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\UpdateLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LocationController::class, 'index'])->name('locations.index');
Route::get('/locations/{slug}', [LocationController::class, 'show'])->name('locations.show');
Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');

/* *****************
 * Logged on users *
 ***************** */
Route::group(['middleware' => ['auth']], function () {
    /* ****************
     * Administrators *
     **************** */
    Route::group(['middleware' => ['admin']], function () {
        Route::resource('update_logs', UpdateLogController::class)->only(['index', 'show']);
    });
});

Auth::routes(['register' => false, 'reset' => false]);
