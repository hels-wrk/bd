<?php

use App\Http\Controllers\DishController;
use App\Http\Controllers\DishRatingController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;

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

Route::middleware('auth:api')->group(function () {
    Route::get('/dishes', [DishController::class, 'index']);
    Route::get('/dishes/{dish}', [DishController::class, 'show']);
    Route::post('/dishes', [DishController::class, 'store']);
    Route::put('/dishes/{dish}', [DishController::class, 'update']);
    Route::delete('/dishes/{dish}', [DishController::class, 'destroy']);

    Route::post('/dishes/{dish}/ratings', [DishRatingController::class, 'rateDish']);
    Route::get('/dishes/{dish}/ratings', [DishRatingController::class, 'getRatings']);
});

Route::post('/oauth/token', [AccessTokenController::class, 'issueToken']);
Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize']);
Route::delete('/oauth/authorize', [AuthorizationController::class, 'deny']);
