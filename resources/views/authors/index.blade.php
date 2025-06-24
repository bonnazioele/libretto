@extends('layouts.app')

@section('title', 'Authors')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Authors</h1>
       @can('create', App\Models\Author::class)
            <a href="{{ route('books.authorCreate') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Author
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-4">
                <form action="{{ route('authors.index') }}" method="GET" class="w-75">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search authors..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                        <a href="{{ route('authors.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                        @endif
                    </div>
                </form>
        
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>@sortablelink('name', 'Name')</th>
                            <th>@sortablelink('birth_date', 'Birth Date')</th>
                            <th>Books</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($authors as $author)
                            <tr>
                                <td>
                                    <a href="{{ route('authors.show', $author) }}">{{ $author->name }}</a>
                                </td>
                                <td>{{ $author->birth_date ? $author->birth_date->format('M d, Y') : 'Unknown' }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $author->books_count }}</span>
                                    <a href="{{ route('books.index', ['author' => $author->id]) }}" class="btn btn-sm btn-link">
                                        <i class="fas fa-book"></i> View Books
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('authors.show', $author) }}" class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('update', $author)
                                            <a href="{{ route('authors.edit', $author) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $author)
                                            <form action="{{ route('authors.destroy', $author) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this author?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-user-edit fa-3x text-muted"></i>
                                        <h4 class="mt-3">No authors found</h4>
                                        @can('create', App\Models\Author::class)
                                        <a href="{{ route('authors.create') }}" class="btn btn-primary mt-3">
                                            <i class="fas fa-plus"></i> Add Your First Author
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($authors->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $authors->firstItem() }} to {{ $authors->lastItem() }} of {{ $authors->total() }} entries
                </div>
                <div>
                    {{ $authors->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .empty-state {
            text-align: center;
            padding: 2rem;
        }
        .table th {
            white-space: nowrap;
        }
        .badge {
            font-size: 0.85em;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Confirm before deleting
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this author?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush