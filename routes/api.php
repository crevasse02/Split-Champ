<?php

use App\Http\Controllers\experimentController;
use App\Http\Controllers\generateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\variantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/get-variant/{eksperimenId}', [HomeController::class, 'getVariantData']);

Route::post('/store-experiment', [experimentController::class, 'store'])->name('store-experiment');

// Variant Controllers Routes
Route::post('/store-variant', [variantController::class, 'store'])->name('store-variant');
Route::post('/check-variant-count', [variantController::class, 'checkVariantCount']);

// Generate code Controllers Routes
Route::delete('/experiment/{id}', [generateController::class, 'destroy'])->name('experiment.destroy');
Route::put('/update-variants/{id}', [generateController::class, 'update']);



