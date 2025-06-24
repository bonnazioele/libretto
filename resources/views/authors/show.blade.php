@extends('layouts.app')

@section('title', $author->name)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>{{ $author->name }}</h1>
                <div class="d-flex gap-2">
                    @can('update', $author)
                        <a href="{{ route('authors.edit', $author) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endcan
                    @can('delete', $author)
                        <form action="{{ route('authors.destroy', $author) }}" method="POST">
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
                    <h5 class="card-title">Biography</h5>
                    <p class="card-text">{{ $author->biography ?? 'No biography available.' }}</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Books by {{ $author->name }}</h5>
                        @can('create', App\Models\Book::class)
                            <a href="{{ route('books.create', ['author_id' => $author->id]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Add Book
                            </a>
                        @endcan
                    </div>
                    
                    @if($author->books->count() > 0)
                        <div class="row">
                            @foreach($author->books as $book)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                                            </h6>
                                            <div class="mb-2">
                                                @foreach($book->genres->take(3) as $genre)
                                                    <span class="badge bg-secondary">{{ $genre->name }}</span>
                                                @endforeach
                                            </div>
                                            <p class="card-text text-muted small">
                                                Published: {{ $book->published_year }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No books found for this author.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Author Details</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Birth Date:</strong> 
                            {{ $author->birth_date ? $author->birth_date->format('M d, Y') : 'Unknown' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Nationality:</strong> {{ $author->nationality ?? 'Unknown' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Total Books:</strong> {{ $author->books_count }}
                        </li>
                        <li class="list-group-item">
                            <strong>Added:</strong> {{ $author->created_at->format('M d, Y') }}
                        </li>
                        @if($author->updated_at->gt($author->created_at))
                            <li class="list-group-item">
                                <strong>Last Updated:</strong> {{ $author->updated_at->format('M d, Y') }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            
            @if($author->photo)
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $author->photo) }}" alt="{{ $author->name }}" class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush