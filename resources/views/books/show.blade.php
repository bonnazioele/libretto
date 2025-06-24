@extends('layouts.app')

@section('title', $book->title)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h1>{{ $book->title }}</h1>
            <p class="lead">by <a href="{{ route('authors.show', $book->author) }}">{{ $book->author->name }}</a></p>
            
            <div class="mb-3">
                @foreach($book->genres as $genre)
                    <span class="badge bg-primary">{{ $genre->name }}</span>
                @endforeach
            </div>
            
            <p>{{ $book->description }}</p>
            
            <div class="mt-4">
                <h3>Reviews</h3>
                            @auth
                <div class="mt-4">
                    <a href="{{ route('reviews.create', $book) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Review
                    </a>
                </div>
            @endauth
                
                @forelse($book->reviews as $review)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                            <h5>{{ $review->user->name ?? 'Anonymous' }}</h5>
                                <div class="text-muted">{{ $review->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <span class="text-warning">★</span>
                                    @else
                                        <span class="text-secondary">★</span>
                                    @endif
                                @endfor
                            </div>
                            <p>{{ $review->comment }}</p>
                        </div>
                    </div>
                @empty
                    <p>No reviews yet.</p>
                @endforelse
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Book Details</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Published:</strong> {{ $book->published_year }}</li>
                        <li class="list-group-item"><strong>Pages:</strong> {{ $book->pages }}</li>
                        <li class="list-group-item"><strong>ISBN:</strong> {{ $book->isbn }}</li>
                    </ul>
                    
                    @auth
                        <div class="mt-3 d-flex gap-2">
                            <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('books.destroy', $book) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection