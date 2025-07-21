<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return response()->json([
            'reviews' => Review::with(['book', 'user'])->latest()->get()
        ]);
    }

    public function bookReviews(Book $book)
    {
        return response()->json([
            'reviews' => $book->reviews()->with('user')->latest()->get()
        ]);
    }
}