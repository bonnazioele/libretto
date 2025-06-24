@extends('layouts.app')

@section('title', 'Welcome to LIBRETTO')

@section('content')
    <div class="jumbotron bg-light p-5 mb-4 rounded-3">
        <div class="container">
            <h1 class="display-4">Welcome to LIBRETTO</h1>
            <p class="lead">Your personal book management system</p>
            <hr class="my-4">
            <p>Track your reading collection, discover new books, and share your reviews with the community.</p>
            <div class="d-flex gap-2">
                <a class="btn btn-primary btn-lg" href="{{ route('books.index') }}" role="button">Browse Books</a>
                @guest
                    <a class="btn btn-outline-primary btn-lg" href="{{ route('register') }}" role="button">Join Now</a>
                @endguest
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-book fa-3x mb-3 text-primary"></i>
                        <h3 class="card-title">Book Collection</h3>
                        <p class="card-text">Manage your personal library and keep track of all your books in one place.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-user-edit fa-3x mb-3 text-primary"></i>
                        <h3 class="card-title">Author Profiles</h3>
                        <p class="card-text">Explore detailed author information and discover their complete works.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-star fa-3x mb-3 text-primary"></i>
                        <h3 class="card-title">Reviews</h3>
                        <p class="card-text">Share your thoughts and read reviews from other book enthusiasts.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Recently Added Books</h4>
                    </div>
                    <div class="card-body">
                        @if($recentBooks->count() > 0)
                            <div class="list-group">
                                @foreach($recentBooks as $book)
                                    <a href="{{ route('books.show', $book) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $book->title }}</h5>
                                            <small>{{ $book->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">by {{ $book->author->name }}</p>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No books added yet.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Top Rated Books</h4>
                    </div>
                    <div class="card-body">
                        @if($topRatedBooks->count() > 0)
                            <div class="list-group">
                                @foreach($topRatedBooks as $book)
                                    <a href="{{ route('books.show', $book) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $book->title }}</h5>
                                            <div>
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= round($book->average_rating))
                                                        <span class="text-warning">★</span>
                                                    @else
                                                        <span class="text-secondary">★</span>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="mb-1">by {{ $book->author->name }}</p>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No reviews yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .jumbotron {
            background-image: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
@endpush