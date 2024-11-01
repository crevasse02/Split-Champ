<?php

use App\Http\Controllers\experimentController;
use App\Http\Controllers\generateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TrackerController;
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
// API
Route::post('/tracker-api', [TrackerController::class, 'tracker']);
Route::post('/view-api', [TrackerController::class, 'view']);


Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    // Experimen Controller Routes
    Route::get('/create-experiment', [experimentController::class, 'index']);

    // Variant Controllers Routes
    Route::get('/form-variant', [variantController::class, 'index']);

    // Generate code Controllers Routes
    Route::get('/generate-code', [generateController::class, 'index'])->name('generate-code');

});
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');
Route::post('/login-process', [LoginController::class, 'login_process'])->name('login-process');
Route::post('/register', [RegisterController::class, 'registerForm']);

Route::fallback(function () {
    return redirect('/'); // Redirect to homepage or any other route
    // Alternatively, show a 404 page
    // return response()->view('errors.404', [], 404);
});

