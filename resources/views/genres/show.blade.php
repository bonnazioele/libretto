@extends('layouts.app')

@section('title', $genre->name)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>{{ $genre->name }}</h1>
                <div class="d-flex gap-2">
                    @can('update', $genre)
                        <a href="{{ route('genres.edit', $genre) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endcan
                    @can('delete', $genre)
                        <form action="{{ route('genres.destroy', $genre) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Description</h5>
                    <p class="card-text">{{ $genre->description ?? 'No description available.' }}</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Books in {{ $genre->name }}</h5>
                    </div>
                    
                    @if($genre->books->count() > 0)
                        <div class="row">
                            @foreach($genre->books as $book)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                                            </h6>
                                            <p class="card-text">
                                                by <a href="{{ route('authors.show', $book->author) }}">{{ $book->author->name }}</a>
                                            </p>
                                            <p class="card-text text-muted small">
                                                Published: {{ $book->published_year }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
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
                <div class="card-body">
                    <h5 class="card-title">Genre Details</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Total Books:</strong> {{ $genre->books_count }}
                        </li>
                        <li class="list-group-item">
                            <strong>Added:</strong> {{ $genre->created_at->format('M d, Y') }}
                        </li>
                        @if($genre->updated_at->gt($genre->created_at))
                            <li class="list-group-item">
                                <strong>Last Updated:</strong> {{ $genre->updated_at->format('M d, Y') }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush