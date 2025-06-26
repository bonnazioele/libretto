@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Review for {{ $review->book->title }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reviews.update', $review) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select class="form-select @error('rating') is-invalid @enderror" 
                                    id="rating" name="rating" required>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                                        {{ $i }} star{{ $i > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Review</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="5" required>{{ old('content', $review->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('books.show', $review->book) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection