@extends('layouts.app')

@section('title', 'Gestion des tables')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Gestion des tables</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tables du restaurant</h5>
                </div>
                <div class="card-body">
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
                                @foreach($tables as $table)
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
                                            <form action="{{ route('restaurant.tables.destroy', [$restaurant, $table]) }}" 
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette table ?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ajouter une table</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('restaurant.tables.store', $restaurant) }}" method="POST">
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
