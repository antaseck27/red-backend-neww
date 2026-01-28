<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HotelController;

/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| HÔTELS (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::get('hotels', [HotelController::class, 'index']);

/*
|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES (JWT)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () { 
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('hotels', [HotelController::class, 'store']);
});
