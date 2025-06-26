@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $book->title }}</h1>
        <div class="btn-group">
            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-secondary">Edit Book</a>
            <form action="{{ route('books.destroy', $book->id) }}" method="POST">
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
                <div class="card-body">
                    <h5 class="card-title">Book Details</h5>
                    <dl class="row">
                        <dt class="col-sm-3">Author</dt>
                        <dd class="col-sm-9">
                            <a href="{{ route('authors.edit', $book->author_id) }}">{{ $book->author->name }}</a>
                        </dd>

                        <dt class="col-sm-3">Genres</dt>
                        <dd class="col-sm-9">
                            @foreach($book->genres as $genre)
                                <a href="{{ route('genres.edit', $genre->id) }}" class="badge bg-info text-dark text-decoration-none">
                                    {{ $genre->name }}
                                </a>
                            @endforeach
                        </dd>

                        <dt class="col-sm-3">Average Rating</dt>
                        <dd class="col-sm-9">
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
                            ({{ number_format($book->reviews->avg('rating'), 1) }} from {{ $book->reviews->count() }} reviews)
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-4">
    <div class="card-header">
        <h5>Reviews</h5>
    </div>
    <div class="card-body">
        @if($book->reviews->count() > 0)
            @foreach($book->reviews as $review)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $review->user ? $review->user->name : 'Anonymous' }}</strong>
                                <span class="text-warning ms-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </span>
                            </div>
                            <div>
                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                @can('update', $review)
                                    <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-outline-secondary ms-2">Edit</a>
                                @endcan
                                @can('delete', $review)
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                        <p class="mt-2">{{ $review->content }}</p>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-muted">No reviews yet.</p>
        @endif

        <div class="mt-4">
            <a href="{{ route('reviews.create', $book) }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Review
            </a>
        </div>
    </div>
</div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Back to All Books</a>
                        <a href="{{ route('reviews.create', $book->id) }}" class="btn btn-primary">Add Review</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection