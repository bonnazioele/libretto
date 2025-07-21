<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ReviewController;

use App\Models\Review;
use App\Models\Book;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::get('/books/{book}/reviews', [ReviewController::class, 'bookReviews']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    
    Route::apiResource('books', BookController::class);
    
});