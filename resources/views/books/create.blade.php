@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h1>Add New Book</h1>
        </div>
    </div>

    <form action="{{ route('books.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="author_id">Author</label>
            <select class="form-control" id="author_id" name="author_id" required>
                <option value="">Select Author</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label>Genres</label>
            <div class="row">
                @foreach($genres as $genre)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="genres[]" value="{{ $genre->id }}" id="genre_{{ $genre->id }}">
                            <label class="form-check-label" for="genre_{{ $genre->id }}">
                                {{ $genre->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Save Book</button>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection