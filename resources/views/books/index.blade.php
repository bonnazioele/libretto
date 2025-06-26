@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Book Collection</h1>
        <div>
            <a href="{{ route('authors.index') }}" class="btn btn-outline-primary me-2">View Authors</a>
            <a href="{{ route('genres.index') }}" class="btn btn-outline-primary me-2">View Genres</a>
            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add New Book
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($books as $book)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">{{ $book->title }}</span>
                        <div>
                            <small class="text-muted">by 
                                <a href="{{ route('authors.edit', $book->author_id) }}" class="text-decoration-none">
                                    {{ $book->author->name }}
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        @foreach($book->genres as $genre)
                            <a href="{{ route('genres.edit', $genre->id) }}" class="badge bg-info text-dark text-decoration-none">
                                {{ $genre->name }}
                            </a>
                        @endforeach
                    </div>
                    
                    <div class="mb-3">
                        <span class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($book->reviews->avg('rating')))
                                    <i class="bi bi-star-fill"></i>
                                @elseif($i - 0.5 <= $book->reviews->avg('rating'))
                                    <i class="bi bi-star-half"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </span>
                        <small class="text-muted">({{ $book->reviews->count() }} reviews)</small>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                        <div class="btn-group">
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $books->links() }}
    </div>
</div>
@endsection