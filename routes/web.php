<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/registerView', function () {
    return view('registerView');
})->name('registerView');
Route::get('/loginView', function () {
    return view('loginView');
})->name('loginView');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
