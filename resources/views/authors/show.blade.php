@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Author: {{ $author->name }}</h1>
        <div class="btn-group">
            <a href="{{ route('authors.edit', $author->id) }}" class="btn btn-secondary">Edit Author</a>
            <form action="{{ route('authors.destroy', $author->id) }}" method="POST">
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
                    <h5 class="mb-0">Books by this Author</h5>
                </div>
                <div class="card-body">
                    @if($author->books->count() > 0)
                        <div class="list-group">
                            @foreach($author->books as $book)
                                <a href="{{ route('books.show', $book->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $book->title }}</h6>
                                        <small class="text-muted">
                                            {{ $book->reviews->count() }} reviews
                                        </small>
                                    </div>
                                    <div class="mb-1">
                                        @foreach($book->genres as $genre)
                                            <span class="badge bg-info text-dark">{{ $genre->name }}</span>
                                        @endforeach
                                    </div>
                                    <small class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($book->reviews->avg('rating')))
                                                <i class="bi bi-star-fill"></i>
                                            @elseif($i - 0.5 <= $book->reviews->avg('rating'))
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No books found for this author.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Author Statistics</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-6">Total Books</dt>
                        <dd class="col-sm-6">{{ $author->books->count() }}</dd>

                        <dt class="col-sm-6">Average Rating</dt>
                        <dd class="col-sm-6">
                            @if($author->books->count() > 0)
                                {{ number_format($author->books->avg(function($book) { return $book->reviews->avg('rating'); }), 1) }}/5
                            @else
                                N/A
                            @endif
                        </dd>

                        <dt class="col-sm-6">Total Reviews</dt>
                        <dd class="col-sm-6">
                            {{ $author->books->sum(function($book) { return $book->reviews->count(); }) }}
                        </dd>
                    </dl>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('books.create') }}?author_id={{ $author->id }}" class="btn btn-primary">
                            Add New Book by This Author
                        </a>
                        <a href="{{ route('authors.index') }}" class="btn btn-outline-secondary">
                            Back to All Authors
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection