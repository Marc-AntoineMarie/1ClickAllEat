@extends('layouts.app')

@section('title', 'Gestion des avis')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Gestion des avis</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Restaurant</th>
                            <th>Client</th>
                            <th>Note</th>
                            <th>Commentaire</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ratings as $rating)
                            <tr>
                                <td>{{ $rating->id }}</td>
                                <td>
                                    <a href="{{ route('restaurants.show', $rating->restaurant) }}">
                                        {{ $rating->restaurant->name }}
                                    </a>
                                </td>
                                <td>{{ $rating->user->name }}</td>
                                <td>
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $rating->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </span>
                                </td>
                                <td>{{ Str::limit($rating->comment, 50) }}</td>
                                <td>{{ $rating->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.ratings.destroy', $rating) }}" 
                                        method="POST" 
                                        class="d-inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $ratings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
