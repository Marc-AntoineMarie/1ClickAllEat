@extends('layouts.app')

@section('title', 'Gestion des tables - ' . $restaurant->name)

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Gestion des tables - {{ $restaurant->name }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tables du restaurant</h5>
                </div>
                <div class="card-body">
                    @if($restaurant->tables->isEmpty())
                        <div class="alert alert-info">
                            Aucune table n'a été créée pour ce restaurant.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Numéro</th>
                                        <th>Capacité</th>
                                        <th>Statut</th>
                                        <th>Réservation en cours</th>
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
                                                @if($table->currentOrder)
                                                    {{ $table->currentOrder->dateReservation->format('d/m/Y H:i') }}
                                                    ({{ $table->currentOrder->number_of_guests }} pers.)
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal" data-bs-target="#editTableModal{{ $table->id }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('restaurants.tables.destroy', [$restaurant, $table]) }}" 
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette table ?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Modal de modification -->
                                                <div class="modal fade" id="editTableModal{{ $table->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Modifier la table {{ $table->number }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ route('restaurants.tables.update', [$restaurant, $table]) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="number{{ $table->id }}" class="form-label">Numéro de table</label>
                                                                        <input type="number" class="form-control" 
                                                                            id="number{{ $table->id }}" name="number" 
                                                                            value="{{ $table->number }}" min="1" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="capacity{{ $table->id }}" class="form-label">Capacité (personnes)</label>
                                                                        <input type="number" class="form-control" 
                                                                            id="capacity{{ $table->id }}" name="capacity" 
                                                                            value="{{ $table->capacity }}" min="1" max="12" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ajouter une table</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('restaurants.tables.store', $restaurant) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="number" class="form-label">Numéro de table</label>
                            <input type="number" class="form-control @error('number') is-invalid @enderror" 
                                id="number" name="number" min="1" required>
                            @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacité (personnes)</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                id="capacity" name="capacity" min="1" max="12" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Ajouter la table</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
