<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('/update_dark_mode_status', function (Request $request) {
    $cacheKey = getDarkModeCacheKeyForUser($request);

    $currentMode = Cache::get($cacheKey);

    $newMode = null;
    if (!$currentMode) {
        $newMode = 'dark';
    } else if ($currentMode === 'dark') {
        $newMode = 'light';
    }

    Cache::set($cacheKey, $newMode);

    return response()->json(['mode' => $newMode]);
});
