<?php

use App\Http\Controllers\experimentController;
use App\Http\Controllers\generateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\variantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
Route::post('/base-view-api', [TrackerController::class, 'viewBaseUrl']);
Route::get('/get-domain/{eksperimenId}', [TrackerController::class, 'getDomainUrl']);




Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    // Experimen Controller Routes
    Route::get('/create-experiment', [experimentController::class, 'index']);

    // Variant Controllers Routes
    Route::get('/form-variant', [variantController::class, 'index']);

    // Generate code Controllers Routes
    Route::get('/generate-code', [generateController::class, 'index'])->name('generate-code');
    // Route::post('/variants/update', [generateController::class, 'update']);

    // Show Registration Form (GET)
    Route::get('/register', function () {
        if (Auth::check() && Auth::user()->email === 'raffi@gmail.com') {
            // Redirect to the registration form method in RegisterController
            return (new RegisterController)->showRegistrationForm();
        }

        // Redirect if the user is unauthorized
        return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
    })->name('register');

    // Handle Registration Form Submission (POST)
    Route::post('/register', function (Request $request) {
        if (Auth::check() && Auth::user()->email === 'raffi@gmail.com') {
            // Call the registerForm method in RegisterController
            return (new RegisterController)->registerForm($request);
        }

        // Redirect if the user is unauthorized
        return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
    });
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');
Route::post('/login-process', [LoginController::class, 'login_process'])->name('login-process');


Route::fallback(function () {
    return redirect('/'); // Redirect to homepage or any other route
    // Alternatively, show a 404 page
    // return response()->view('errors.404', [], 404);
});
