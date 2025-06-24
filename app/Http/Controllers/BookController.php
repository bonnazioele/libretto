<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;  

class BookController extends Controller
{
    public function index(): View
    {
        return view('books.index', [
            'books' => Book::with(['author', 'genres'])->latest()->paginate(10)
        ]);
    }

    public function create(): View
    {
        return view('books.create', [
            'authors' => Author::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get()
        ]);
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        $book = Book::create([
            'title' => $validated['title'],
            'author_id' => $validated['author_id']
        ]);

        if (isset($validated['genres'])) {
            $book->genres()->attach($validated['genres']);
        }

        return redirect()->route('books.index')
            ->withSuccess('New book is added successfully.');
    }

    public function show(Book $book): View
    {
        $book->load(['author', 'genres', 'reviews']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        return view('books.edit', [
            'book' => $book,
            'authors' => Author::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
            'selectedGenres' => $book->genres->pluck('id')->toArray()
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $validated = $request->validated();

        $book->update([
            'title' => $validated['title'],
            'author_id' => $validated['author_id']
        ]);

        $book->genres()->sync($validated['genres'] ?? []);

        return redirect()->back()
            ->withSuccess('Book is updated successfully.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();
        return redirect()->route('books.index')
            ->withSuccess('Book is deleted successfully.');
    }

    // Add these methods to your existing BookController

public function authorIndex(): View
{
    return view('authors.index', [
        'authors' => Author::withCount('books')->latest()->paginate(10)
    ]);
}

public function authorCreate(): View
{
    return view('authors.create');
}

public function authorStore(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    Author::create($validated);

    return redirect()->route('authors.index')
        ->withSuccess('New author added successfully.');
}

public function authorShow(Author $author): View
{
    $author->load('books'); // Eager load the books relationship
    return view('authors.show', compact('author'));
}

public function genreIndex(): View
{
    return view('genres.index', [
        'genres' => Genre::withCount('books')->latest()->paginate(10)
    ]);
}

public function genreCreate(): View
{
    return view('genres.create');
}

public function genreStore(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:genres',
    ]);

    Genre::create($validated);

    return redirect()->route('genres.index')
        ->withSuccess('New genre added successfully.');
}

public function genreShow(Author $author): View
{
    $author->load('books'); // Eager load the books relationship
    return view('genre.show', compact('genre'));
}

public function reviewCreate(Book $book): View
{
    return view('reviews.create', compact('book'));
}

public function reviewStore(Request $request, Book $book): RedirectResponse
{
    $validated = $request->validate([
        'content' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    // Create review with only the model's fillable fields
    $book->reviews()->create([
        'content' => $validated['content'],
        'rating' => $validated['rating'],
        // book_id is automatically set from the relationship
    ]);

    return redirect()->route('books.show', $book)
        ->with('success', 'Review added successfully!');
}

public function reviewEdit(Review $review): View
{
    return view('reviews.edit', compact('review'));
}

public function reviewUpdate(Request $request, Review $review): RedirectResponse
{
    $validated = $request->validate([
        'content' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    $review->update($validated);

    return redirect()->route('books.show', $review->book)
        ->with('success', 'Review updated successfully!');
}

public function reviewDestroy(Review $review): RedirectResponse
{
    $book = $review->book;
    $review->delete();

    return redirect()->route('books.show', $book)
        ->with('success', 'Review deleted successfully!');
}
}

