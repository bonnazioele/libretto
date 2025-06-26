<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use Illuminate\Http\Request;

class BookController extends Controller
{
   

    // Book methods
    public function index()
    {
        $books = Book::with(['author', 'genres', 'reviews'])->paginate(10);
        return view('books.index', compact('books'));
    }

    public function create()
    {
        $authors = Author::all();
        $genres = Genre::all();
        return view('books.create', compact('authors', 'genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'genres' => 'array',
            'genres.*' => 'exists:genres,id'
        ]);

        $book = Book::create($validated);
        
        if (isset($validated['genres'])) {
            $book->genres()->attach($validated['genres']);
        }

        return redirect()->route('books.show', $book)->with('success', 'Book created successfully!');
    }

    public function show(Book $book)
    {
        $book->load(['author', 'genres', 'reviews']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $authors = Author::all();
        $genres = Genre::all();
        return view('books.edit', compact('book', 'authors', 'genres'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'genres' => 'array',
            'genres.*' => 'exists:genres,id'
        ]);

        $book->update($validated);
        
        if (isset($validated['genres'])) {
            $book->genres()->sync($validated['genres']);
        } else {
            $book->genres()->detach();
        }

        return redirect()->route('books.show', $book)->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }

    // Author methods
    public function authorIndex()
    {
        $authors = Author::paginate(10);
        return view('authors.index', compact('authors'));
    }

    public function authorCreate()
    {
        return view('authors.create');
    }

    public function authorStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Author::create($validated);

        return redirect()->route('authors.index')->with('success', 'Author created successfully!');
    }

    public function authorEdit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    public function authorUpdate(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $author->update($validated);

        return redirect()->route('authors.index')->with('success', 'Author updated successfully!');
    }

    public function authorDestroy(Author $author)
    {
        if ($author->books()->count() > 0) {
            return back()->with('error', 'Cannot delete author with existing books!');
        }

        $author->delete();
        return redirect()->route('authors.index')->with('success', 'Author deleted successfully!');
    }

    // Genre methods
    public function genreIndex()
    {
        $genres = Genre::paginate(10);
        return view('genres.index', compact('genres'));
    }

    public function genreCreate()
    {
        return view('genres.create');
    }

    public function genreStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:genres',
        ]);

        Genre::create($validated);

        return redirect()->route('genres.index')->with('success', 'Genre created successfully!');
    }

    public function genreEdit(Genre $genre)
    {
        return view('genres.edit', compact('genre'));
    }

    public function genreUpdate(Request $request, Genre $genre)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,'.$genre->id,
        ]);

        $genre->update($validated);

        return redirect()->route('genres.index')->with('success', 'Genre updated successfully!');
    }

    public function genreDestroy(Genre $genre)
    {
        if ($genre->books()->count() > 0) {
            return back()->with('error', 'Cannot delete genre with existing books!');
        }

        $genre->delete();
        return redirect()->route('genres.index')->with('success', 'Genre deleted successfully!');
    }

    // Review methods
    public function reviewCreate(Book $book)
    {
        return view('reviews.create', compact('book'));
    }

    public function reviewStore(Request $request, Book $book)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        $book->reviews()->create($validated + ['user_id' => auth()->id()]);

        return redirect()->route('books.show', $book)->with('success', 'Review added successfully!');
    }

    public function reviewEdit(Review $review)
    {
        return view('reviews.edit', compact('review'));
    }

    public function reviewUpdate(Request $request, Review $review)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        $review->update($validated);

        return redirect()->route('books.show', $review->book)->with('success', 'Review updated successfully!');
    }

    public function reviewDestroy(Review $review)
    {
        $book = $review->book;
        $review->delete();

        return redirect()->route('books.show', $book)->with('success', 'Review deleted successfully!');
    }
}