<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\SysUserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [SysUserController::class, 'showLoginForm'])->name('login');
    Route::post('login', [SysUserController::class, 'login'])->name('login.attempt');
    Route::get('register', [SysUserController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [SysUserController::class, 'register'])->name('register.store');
});

Route::post('logout', [SysUserController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Home
    Route::get('/', [BookController::class, 'index'])->name('home');
    
    // Books Resource
    Route::resource('books', BookController::class);
    
    // Authors Routes (plural)
    Route::prefix('authors')->group(function () {
        Route::get('/', [BookController::class, 'authorIndex'])->name('authors.index');
        Route::get('/create', [BookController::class, 'authorCreate'])->name('authors.create');
        Route::post('/', [BookController::class, 'authorStore'])->name('authors.store');
        Route::get('/{author}/edit', [BookController::class, 'authorEdit'])->name('authors.edit');
        Route::put('/{author}', [BookController::class, 'authorUpdate'])->name('authors.update');
        Route::delete('/{author}', [BookController::class, 'authorDestroy'])->name('authors.destroy');
        Route::get('/{author}', [BookController::class, 'authorShow'])->name('authors.show');
    });
    
    // Genres Routes (plural)
    Route::prefix('genres')->group(function () {
        Route::get('/', [BookController::class, 'genreIndex'])->name('genres.index');
        Route::get('/create', [BookController::class, 'genreCreate'])->name('genres.create');
        Route::post('/', [BookController::class, 'genreStore'])->name('genres.store');
        Route::get('/{genre}/edit', [BookController::class, 'genreEdit'])->name('genres.edit');
        Route::put('/{genre}', [BookController::class, 'genreUpdate'])->name('genres.update');
        Route::delete('/{genre}', [BookController::class, 'genreDestroy'])->name('genres.destroy');
        Route::get('/{genre}', [BookController::class, 'genreShow'])->name('genres.show');
    });
    
    // Reviews Routes
    Route::prefix('books/{book}')->group(function () {
        Route::get('/reviews/create', [BookController::class, 'reviewCreate'])->name('reviews.create');
        Route::post('/reviews', [BookController::class, 'reviewStore'])->name('reviews.store');
    });
    
    Route::prefix('reviews')->group(function () {
        Route::get('/{review}/edit', [BookController::class, 'reviewEdit'])->name('reviews.edit');
        Route::put('/{review}', [BookController::class, 'reviewUpdate'])->name('reviews.update');
        Route::delete('/{review}', [BookController::class, 'reviewDestroy'])->name('reviews.destroy');
    });
});