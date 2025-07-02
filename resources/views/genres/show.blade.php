@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Genre: {{ $genre->name }}</h1>
        <div class="btn-group">
            <a href="{{ route('genres.edit', $genre->id) }}" class="btn btn-secondary">Edit Genre</a>
            <form action="{{ route('genres.destroy', $genre->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Books in this Genre</h5>
                </div>
                <div class="card-body">
                    @if($genre->books->count() > 0)
                        <div class="list-group">
                            @foreach($genre->books as $book)
                                <a href="{{ route('books.show', $book->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $book->title }}</h6>
                                        <small class="text-muted">
                                            by {{ $book->author->name }}
                                        </small>
                                    </div>
                                    <div class="mb-1">
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
                                        <small class="text-muted ms-2">
                                            ({{ $book->reviews->count() }} reviews)
                                        </small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No books found in this genre.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Genre Statistics</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-6">Total Books</dt>
                        <dd class="col-sm-6">{{ $genre->books->count() }}</dd>

                        <dt class="col-sm-6">Average Rating</dt>
                        <dd class="col-sm-6">
                            @if($genre->books->count() > 0)
                                {{ number_format($genre->books->avg(function($book) { return $book->reviews->avg('rating'); }), 1) }}/5
                            @else
                                N/A
                            @endif
                        </dd>

                        <dt class="col-sm-6">Total Reviews</dt>
                        <dd class="col-sm-6">
                            {{ $genre->books->sum(function($book) { return $book->reviews->count(); }) }}
                        </dd>
                    </dl>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('books.create') }}" class="btn btn-primary">
                            Add New Book in This Genre
                        </a>
                        <a href="{{ route('genres.index') }}" class="btn btn-outline-secondary">
                            Back to All Genres
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection