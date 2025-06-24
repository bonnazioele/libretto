<div class="mt-5">
    <h3>Reviews</h3>
    @foreach($book->reviews as $review)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>Rating:</strong> {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                    </div>
                    @auth
                        <div>
                            <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    @endauth
                </div>
                <p class="mt-2">{{ $review->content }}</p>
            </div>
        </div>
    @endforeach
    
    @auth
        <a href="{{ route('reviews.create', $book) }}" class="btn btn-primary">Add Review</a>
    @endauth
</div>