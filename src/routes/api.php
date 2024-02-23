<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowController;
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

Route::prefix('/user')->group(function () {
    Route::post('/', [UserController::class, 'store'])->name('user.store');
    Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::prefix('/user')->group(function () {
        Route::get('/{id}/followers', [FollowController::class, 'getFollowers'])->name('follow.getFollowers');
        Route::get('/{id}/followed', [FollowController::class, 'getFollowed'])->name('follow.getFollowed');
    });

    Route::delete('/follower/{id}/remove', [FollowController::class, 'removeFollower'])->name('follow.removeFollower');
    Route::delete('/followed/{id}/remove', [FollowController::class, 'stopFollowing'])->name('follow.stopFollowing');

    Route::apiResource('user', UserController::class)->except(['store', 'show']);
    Route::apiResource('follow', FollowController::class);
});
