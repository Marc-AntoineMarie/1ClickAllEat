@extends('layouts.app')

@section('title', 'Gestion des restaurants')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Gestion des restaurants</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Propriétaire</th>
                            <th>Adresse</th>
                            <th>Commandes</th>
                            <th>Note moyenne</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($restaurants as $restaurant)
                            <tr>
                                <td>{{ $restaurant->id }}</td>
                                <td>
                                    <a href="{{ route('restaurants.show', $restaurant) }}">
                                        {{ $restaurant->name }}
                                    </a>
                                </td>
                                <td>{{ $restaurant->owner->name }}</td>
                                <td>{{ $restaurant->address }}</td>
                                <td>{{ $restaurant->orders_count }}</td>
                                <td>
                                    @if($restaurant->ratings_count > 0)
                                        <span class="text-warning">
                                            {{ number_format($restaurant->ratings()->avg('score'), 1) }}
                                            <i class="bi bi-star-fill"></i>
                                        </span>
                                        <small>({{ $restaurant->ratings_count }})</small>
                                    @else
                                        <span class="text-muted">Aucun avis</span>
                                    @endif
                                </td>
                                <td>{{ $restaurant->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('restaurants.edit', $restaurant) }}" 
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('restaurants.destroy', $restaurant) }}" 
                                            method="POST" 
                                            class="d-inline"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce restaurant ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $restaurants->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
