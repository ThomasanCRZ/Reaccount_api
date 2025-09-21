<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Authentification
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Transactions (CRUD)
    // Route::apiResource('transactions', TransactionController::class);
    // Ou explicitement :
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions/store', [TransactionController::class, 'store']);
    //Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    // Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    // Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
});

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);