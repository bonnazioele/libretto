@extends('layouts.app')

@section('title', 'Genres')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Genres</h1>
        @can('create', App\Models\Genre::class)
            <a href="{{ route('genres.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Genre
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('genres.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search genres..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Books</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($genres as $genre)
                            <tr>
                                <td>
                                    <a href="{{ route('genres.show', $genre) }}">{{ $genre->name }}</a>
                                </td>
                                <td>{{ Str::limit($genre->description, 50) }}</td>
                                <td>{{ $genre->books_count }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('genres.show', $genre) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('update', $genre)
                                            <a href="{{ route('genres.edit', $genre) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $genre)
                                            <form action="{{ route('genres.destroy', $genre) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No genres found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $genres->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush