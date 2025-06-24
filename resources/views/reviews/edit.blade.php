@extends('layouts.app')

@section('title', 'Edit Review')

@section('content')
<div class="container">
    <h1>Edit Review for {{ $review->book->title }}</h1>
    
    <form method="POST" action="{{ route('reviews.update', $review) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="content">Review Content</label>
            <textarea id="content" name="content" class="form-control" required>{{ old('content', $review->content) }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="rating">Rating (1-5)</label>
            <select id="rating" name="rating" class="form-control" required>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary mt-3">Update Review</button>
    </form>
</div>
@endsection