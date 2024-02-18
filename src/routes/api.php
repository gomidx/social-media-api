<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------a
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/token', [AuthController::class, 'generateToken'])->name('auth.generateToken');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::apiResource('user', UserController::class)->except(['store', 'show']);
});
