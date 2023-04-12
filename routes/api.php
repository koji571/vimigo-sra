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

    // Route for /student for update
    Route::put('/student', [StudentController::class, 'update']);

    // Route for /student for delete
    Route::delete('/student', [StudentController::class, 'destroy']);

    // Route for get for index
    Route::get('/student', [StudentController::class, 'index']);

    //Route for importing data to create new Student data
    Route::post('/import', [ImportController::class, 'import']);
    //Route for importing data to delete old Student data
    Route::post('/delete', [ImportController::class, 'delete']);
    //Route for importing data to update old Student data
    Route::post('/update', [ImportController::class, 'update']);

    }
);
