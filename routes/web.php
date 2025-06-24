<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\SysUserController;
use Illuminate\Support\Facades\Route;


// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [SysUserController::class, 'login'])->name('login');
    Route::post('login', [SysUserController::class, 'authenticate']);
    Route::get('register', [SysUserController::class, 'register'])->name('register');
    Route::post('register', [SysUserController::class, 'store']);
});

Route::post('logout', [SysUserController::class, 'logout'])->name('logout');

// All Resource Routes in BookController
Route::middleware('auth')->group(function () {
    // Books
    Route::resource('books', BookController::class);
    
    // Authors
    Route::get('/authors', [BookController::class, 'authorIndex'])->name('authors.index');
    Route::get('/authors/create', [BookController::class, 'authorCreate'])->name('authors.create');
    Route::post('/authors', [BookController::class, 'authorShow'])->name('authors.store');
    Route::post('/authors', [BookController::class, 'authorStore'])->name('authors.show');


    // Route::get('/', [BookController::class, 'authorIndex'])->name('books.authorIndex');
    // Route::get('/create', [BookController::class, 'authorCreate'])->name('books.authorCreate');
    // Route::post('/', [BookController::class, 'authorStore'])->name('books.authorStore');
    // Route::get('/{author}', [BookController::class, 'authorShow'])->name('books.authorShow');
    // Route::get('/{author}/edit', [BookController::class, 'authorEdit'])->name('books.authorEdit');
    // Route::put('/{author}', [BookController::class, 'authorUpdate'])->name('books.authorUpdate');
    // Route::delete('/{author}', [BookController::class, 'authorDestroy'])->name('books.authorDestroy');
    
    // Genres
    Route::get('/genres', [BookController::class, 'genreIndex'])->name('genres.index');
    Route::get('/genres/create', [BookController::class, 'genreCreate'])->name('genres.create');
    Route::post('/genres', [BookController::class, 'genreStore'])->name('genres.store');
    Route::post('/genres', [BookController::class, 'genreShow'])->name('genres.show');

    //Reviews
    Route::get('/books/{book}/reviews/create', [BookController::class, 'reviewCreate'])->name('reviews.create');
    Route::post('/books/{book}/reviews', [BookController::class, 'reviewStore'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [BookController::class, 'reviewEdit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [BookController::class, 'reviewUpdate'])->name('reviews.update');
    Route::delete('/reviews/{review}', [BookController::class, 'reviewDestroy'])->name('reviews.destroy');
    
    // Home
    Route::get('/', [BookController::class, 'index'])->name('home');
});