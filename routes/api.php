<?php

use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/token', [TokenController::class, 'index']);
Route::get('/positions', [PositionController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/users', [UserController::class, 'store']);
});
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);

