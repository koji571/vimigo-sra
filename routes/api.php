<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\StudentController;

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

// Route for registration
Route::post('/register', [UserAuthController::class, 'register']);

// Route for login
Route::post('/login', [UserAuthController::class, 'login'])->name('login');

// Routes that require authentication
Route::middleware(['auth:api'])->group(function () {
    // Route for /student/search
    Route::get('/student/search', [StudentController::class, 'show'])->middleware('api');

    //Route for delete
    Route::delete('/students/{name}', [StudentController::class, 'destroy'])->middleware('api');

    // Route for /student/update
    Route::get('/student/{id}', [StudentController::class, 'update'])->middleware('api');

    // Route for get
    Route::get('/student', [StudentController::class, 'index'])->middleware('api');

    // Other routes that require authentication
    Route::apiResource('/student', StudentController::class);
});
