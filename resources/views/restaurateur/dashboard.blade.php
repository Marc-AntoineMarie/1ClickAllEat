@extends('layouts.app')

@section('title', 'Dashboard Restaurateur')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1>Dashboard Restaurateur</h1>
        </div>
    </div>

    @if($restaurants->isEmpty())
        <div class="alert alert-info">
            Vous n'avez pas encore de restaurant.
            <a href="{{ route('restaurants.create') }}" class="alert-link">Créer un restaurant</a>
        </div>
    @else
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="list-group">
                    @foreach($restaurants as $restaurant)
                        <a href="{{ route('restaurateur.dashboard.show', $restaurant) }}" 
                            class="list-group-item list-group-item-action {{ $selectedRestaurant?->id === $restaurant->id ? 'active' : '' }}">
                            {{ $restaurant->name }}
                        </a>
                    @endforeach
                    <a href="{{ route('restaurants.create') }}" class="list-group-item list-group-item-action text-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter un restaurant
                    </a>
                </div>
            </div>

            <div class="col-md-9">
                @if($selectedRestaurant)
                    <div class="row mb-4">
                        <div class="col-12 mb-4">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                                        type="button" role="tab" aria-controls="info" aria-selected="true">
                                        <i class="bi bi-info-circle"></i> Informations
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="menu-tab" data-bs-toggle="tab" data-bs-target="#menu"
                                        type="button" role="tab" aria-controls="menu" aria-selected="false">
                                        <i class="bi bi-list"></i> Menu
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tables-tab" data-bs-toggle="tab" data-bs-target="#tables"
                                        type="button" role="tab" aria-controls="tables" aria-selected="false">
                                        <i class="bi bi-grid"></i> Tables
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <!-- Onglet Informations -->
                            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('restaurants.edit', $selectedRestaurant) }}" class="btn btn-primary">
                                                <i class="bi bi-pencil"></i> Modifier le restaurant
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-white bg-primary">
                                            <div class="card-body">
                                                <h6 class="card-title">Commandes aujourd'hui</h6>
                                                <h3 class="card-text mb-0">{{ $stats['orders']->today_orders ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-white bg-success">
                                            <div class="card-body">
                                                <h6 class="card-title">CA aujourd'hui</h6>
                                                <h3 class="card-text mb-0">{{ number_format($stats['revenue']->today_revenue ?? 0, 2) }} €</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-white bg-info">
                                            <div class="card-body">
                                                <h6 class="card-title">Note moyenne</h6>
                                                <h3 class="card-text mb-0">{{ number_format($stats['rating']['average'], 1) }}/5</h3>
                                                <small>{{ $stats['rating']['count'] }} avis</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-white bg-warning">
                                            <div class="card-body">
                                                <h6 class="card-title">Commandes en attente</h6>
                                                <h3 class="card-text mb-0">{{ $orders->where('status', 'pending')->count() }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Onglet Menu -->
                            <div class="tab-pane fade" id="menu" role="tabpanel" aria-labelledby="menu-tab">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('restaurants.items.create', $selectedRestaurant) }}" class="btn btn-success">
                                                <i class="bi bi-plus-circle"></i> Ajouter un plat
                                            </a>
                                        </div>
                                    </div>
                                    @forelse($selectedRestaurant->items->groupBy('category.name') as $category => $items)
                                        <div class="col-12 mb-4">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h5 class="mb-0">{{ $category }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nom</th>
                                                                    <th>Description</th>
                                                                    <th>Prix</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($items as $item)
                                                                    <tr>
                                                                        <td>{{ $item->name }}</td>
                                                                        <td>{{ $item->description }}</td>
                                                                        <td>{{ number_format($item->prix, 2) }} €</td>
                                                                        <td>
                                                                            <div class="btn-group">
                                                                                <a href="{{ route('restaurants.items.edit', [$selectedRestaurant, $item]) }}" 
                                                                                    class="btn btn-sm btn-outline-primary">
                                                                                    <i class="bi bi-pencil"></i>
                                                                                </a>
                                                                                <form action="{{ route('restaurants.items.destroy', [$selectedRestaurant, $item]) }}" 
                                                                                    method="POST" class="d-inline">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce plat ?')">
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
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                Aucun plat n'a été ajouté au menu.
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Onglet Tables -->
                            <div class="tab-pane fade" id="tables" role="tabpanel" aria-labelledby="tables-tab">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTableModal">
                                                <i class="bi bi-plus-circle"></i> Ajouter une table
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0">Tables du restaurant</h5>
                                            </div>
                                            <div class="card-body">
                                                @if($selectedRestaurant->tables->isEmpty())
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
                                                                @foreach($selectedRestaurant->tables as $table)
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
                                                                                {{ $table->currentOrder->date_reservation->format('d/m/Y H:i') }}
                                                                                ({{ $table->currentOrder->number_of_guests ?? '?' }} pers.)
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
                                                                                <form action="{{ route('restaurants.tables.destroy', ['restaurant' => $selectedRestaurant, 'table' => $table]) }}" 
                                                                                    method="POST" class="d-inline">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette table ?')">
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
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Top 5 des plats</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Plat</th>
                                                    <th>Quantité</th>
                                                    <th>CA</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($stats['top_items'] as $item)
                                                    <tr>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->total_quantity }}</td>
                                                        <td>{{ number_format($item->total_revenue, 2) }} €</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Statistiques</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Période</th>
                                                    <th>Commandes</th>
                                                    <th>CA</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Aujourd'hui</td>
                                                    <td>{{ $stats['orders']->today_orders ?? 0 }}</td>
                                                    <td>{{ number_format($stats['revenue']->today_revenue ?? 0, 2) }} €</td>
                                                </tr>
                                                <tr>
                                                    <td>Cette semaine</td>
                                                    <td>{{ $stats['orders']->week_orders ?? 0 }}</td>
                                                    <td>{{ number_format($stats['revenue']->week_revenue ?? 0, 2) }} €</td>
                                                </tr>
                                                <tr>
                                                    <td>Ce mois</td>
                                                    <td>{{ $stats['orders']->month_orders ?? 0 }}</td>
                                                    <td>{{ number_format($stats['revenue']->month_revenue ?? 0, 2) }} €</td>
                                                </tr>
                                                <tr>
                                                    <td>Total</td>
                                                    <td>{{ $stats['orders']->total_orders ?? 0 }}</td>
                                                    <td>{{ number_format($stats['revenue']->total_revenue ?? 0, 2) }} €</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Commandes en cours</h5>
                        </div>
                        <div class="card-body">
                            @if($orders->isEmpty())
                                <p class="text-muted mb-0">Aucune commande en cours.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Client</th>
                                                <th>Total</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->id }}</td>
                                                    <td>{{ $order->client->name }}</td>
                                                    <td>{{ number_format($order->total_price, 2) }} €</td>
                                                    <td>
                                                        <span class="badge bg-{{ $order->status_color }}">
                                                            {{ $order->status_text }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('orders.show', $order) }}" 
                                                            class="btn btn-sm btn-primary">
                                                            Détails
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Modal d'ajout de table -->
@if($selectedRestaurant)
<div class="modal fade" id="addTableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une table</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('restaurants.tables.store', ['restaurant' => $selectedRestaurant]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="number" class="form-label">Numéro de table</label>
                        <input type="text" class="form-control" id="number" name="number" required>
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacité</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" min="1" max="12" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modales de modification des tables -->
@foreach($selectedRestaurant->tables ?? [] as $table)
    <div class="modal fade" id="editTableModal{{ $table->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la table {{ $table->number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('restaurants.tables.update', ['restaurant' => $selectedRestaurant, 'table' => $table]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="number{{ $table->id }}" class="form-label">Numéro de table</label>
                            <input type="text" class="form-control" id="number{{ $table->id }}" name="number" value="{{ $table->number }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="capacity{{ $table->id }}" class="form-label">Capacité</label>
                            <input type="number" class="form-control" id="capacity{{ $table->id }}" name="capacity" min="1" max="12" value="{{ $table->capacity }}" required>
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
@endforeach
@endif
@endsection
