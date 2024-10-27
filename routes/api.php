<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostController;

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/verify', [AuthController::class, 'verifyByCode']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route::middleware(middleware: 'auth:sanctum')->group(function () {
    Route::apiResource('tags', TagController::class);
// });

// Route::middleware(middleware: 'auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class);
// });