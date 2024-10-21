<?php

use App\Http\Controllers\experimentController;
use App\Http\Controllers\variantController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/login', function () {
    return view('login');
});
Route::get('/register', function () {
    return view('register');
});

// Experimen Controller Routes
Route::get('/create-experiment', [experimentController::class, 'index']);
Route::post('/store-experiment', [experimentController::class, 'store']);

// Variant Controllers Routes
Route::get('/form-variant', [variantController::class, 'index']);
