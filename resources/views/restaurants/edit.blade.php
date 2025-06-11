@extends('layouts.app')

@section('title', 'Modifier ' . $restaurant->name)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modifier le restaurant</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('restaurants.update', $restaurant) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du restaurant</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $restaurant->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $restaurant->adresse) }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $restaurant->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="opening_hours" class="form-label">Horaires d'ouverture</label>
                            <input type="text" class="form-control @error('opening_hours') is-invalid @enderror" id="opening_hours" name="opening_hours" value="{{ old('opening_hours', $restaurant->openHours) }}" required>
                            <div class="form-text">Exemple : Lun-Ven 9h-22h, Sam-Dim 10h-23h</div>
                            @error('opening_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacité (nombre de places)</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $restaurant->capacity) }}" required min="1">
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Mettre à jour le restaurant</button>
                            <a href="{{ route('restaurateur.dashboard.show', $restaurant) }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <h3>Gérer les tables</h3>
                    <div class="table-responsive mb-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Numéro</th>
                                    <th>Capacité</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($restaurant->tables as $table)
                                <tr>
                                    <td>{{ $table->number }}</td>
                                    <td>{{ $table->capacity }} personnes</td>
                                    <td>
                                        <span class="badge bg-{{ $table->is_available ? 'success' : 'danger' }}">
                                            {{ $table->is_available ? 'Disponible' : 'Occupée' }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('restaurants.tables.destroy', [$restaurant, $table]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cette table ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <form action="{{ route('restaurants.tables.store', $restaurant) }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-4">
                            <label for="table_number" class="form-label">Numéro de table</label>
                            <input type="text" class="form-control" id="table_number" name="number" required>
                        </div>
                        <div class="col-md-4">
                            <label for="table_capacity" class="form-label">Capacité</label>
                            <input type="number" class="form-control" id="table_capacity" name="capacity" min="1" max="12" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-success">Ajouter une table</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
