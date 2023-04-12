<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ImportController;

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

    // Other routes that require authentication
    Route::apiResource('/student', StudentController::class);

    // Route for /student/search to search for particular student info
    Route::get('/student/search', [StudentController::class, 'show']);

    // Route for /student/{id} for update and delete
    Route::put('/student/{id}', [StudentController::class, 'update']); // Update student
    Route::delete('/student/{id}', [StudentController::class, 'destroy']); // Delete student

    // Route for get for index
    Route::get('/student', [StudentController::class, 'index']);

    //Route for importing data to create new
    Route::post('/import', [ImportController::class, 'import']);
    //Route for importing data to create new
    Route::post('/delete', [ImportController::class, 'delete']);


});
