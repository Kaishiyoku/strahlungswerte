<?php

use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/', 'LocationController@index')->name('locations.index');
    Route::get('/locations/{slug}', 'LocationController@show')->name('locations.show');

    /* *****************
     * Logged on users *
     ***************** */
    Route::group(['middleware' => ['auth']], function () {

        /* ****************
         * Administrators *
         **************** */
        Route::group(['middleware' => ['admin']], function () {

        });
    });

    Auth::routes(['register' => false, 'reset' => false]);
});
