@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ $author->image_url }}" class="card-img-top" alt="{{ $author->name }}">
                <div class="card-body text-center">
                    <h3 class="card-title">{{ $author->name }}</h3>
                    <div class="d-grid gap-2">
                        <a href="{{ route('authors.edit', $author) }}" class="btn btn-outline-secondary">Edit</a>
                        <form action="{{ route('authors.destroy', $author) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" 
                                    onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <!-- Books by this author section -->
        </div>
    </div>
</div>
@endsection