@extends('layouts.app')

@section('title', 'Add Review')

@section('content')
<div class="container">
    <h1>Add Review for {{ $book->title }}</h1>
    
    <form method="POST" action="{{ route('reviews.store', $book) }}">
        @csrf
        <div class="form-group">
            <label for="content">Review Content</label>
            <textarea id="content" name="content" class="form-control" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="rating">Rating (1-5)</label>
            <select id="rating" name="rating" class="form-control" required>
                <option value="">Select rating</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary mt-3">Submit Review</button>
    </form>
</div>
@endsection