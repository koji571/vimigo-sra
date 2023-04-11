<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers;

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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

// localhost:8000/api/Student
/*
Route::group(['namespace'=>'App\Http\Controllers'], function(){
    Route::apiResources([
        'student' => StudentController::class
    ]);
});
*/

// Route for registration
Route::post('/register', [UserAuthController::class, 'register']);

// Route for login
Route::post('/login', [UserAuthController::class, 'login'])->name('login');

// Routes that require authentication
Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('/student', 'StudentController@index');
});
