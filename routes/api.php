<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

// Auth API routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'registerApi']);
    Route::post('/login', [AuthController::class, 'loginApi']);
});

// User CRUD API routes
Route::prefix('users')->group(function () {
    Route::get('/', [AuthController::class, 'getAllUsersApi']);
    Route::get('/{id}', [AuthController::class, 'getUserByIdApi']);
    Route::put('/{id}', [AuthController::class, 'updateUserApi']);
    Route::delete('/{id}', [AuthController::class, 'deleteUserApi']);
});

// Test route
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now()
    ]);
});
