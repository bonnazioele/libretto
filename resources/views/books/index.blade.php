@extends('layouts.app')

@section('title', 'All Books')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>All Books</h1>
        @auth
            <a href="{{ route('books.create') }}" class="btn btn-primary">Add New Book</a>
        @endauth
    </div>

    <div class="row">
        @foreach($books as $book)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="card-text">by {{ $book->author->name }}</p>
                        <div class="mb-2">
                            @foreach($book->genres as $genre)
                                <span class="badge bg-secondary">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $books->links() }}
@endsection