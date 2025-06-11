@extends('layouts.app')

@section('title', 'Mes commandes')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ (Auth::user()->role && Auth::user()->role->name === 'restaurateur') ? 'Commandes à traiter' : 'Mes commandes' }}</h1>

    @if(Auth::user()->role && Auth::user()->role->name === 'client')
        <div class="mb-4">
            <a href="{{ route('restaurants.index') }}" class="btn btn-success">
                <i class="fa fa-utensils me-1"></i> Voir les restaurants
            </a>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(Auth::user()->role && Auth::user()->role->name === 'client')
        {{-- Vue unifiée pour les clients: réservations et commandes --}}
        @if($orders->isEmpty() && (!isset($reservations) || $reservations->isEmpty()))
            <div class="alert alert-info">
                Vous n'avez pas encore de réservations ou de commandes.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Restaurant</th>
                            <th>Table</th>
                            <th>Personnes</th>
                            <th>Date et heure</th>
                            <th>Statut</th>
                            <th>Type</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Affichage des commandes --}}
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->restaurant->name }}</td>
                                <td>
                                    @if($order->reservation && $order->reservation->table)
                                        Table {{ $order->reservation->table->number }}
                                        <small class="text-muted d-block">({{ $order->reservation->table->capacity }} places)</small>
                                    @else
                                        Non assignée
                                    @endif
                                </td>
                                <td>{{ $order->reservation->nb_personnes ?? '-' }} pers.</td>
                                <td>
                                    @if($order->reservation && $order->reservation->date_reservation)
                                        {{ \Carbon\Carbon::parse($order->reservation->date_reservation)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                    @if($order->special_requests)
                                        <i class="bi bi-info-circle text-info" title="{{ $order->special_requests }}"></i>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->status_color }}">
                                        {{ $order->status_text }}
                                    </span>
                                </td>
                                <td><span class="badge bg-primary">Commande</span></td>
                                <td>{{ number_format($order->total_price, 2) }} €</td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">
                                        Détails
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        
                        {{-- Affichage des réservations sans commandes --}}
                        @if(isset($reservations))
                            @foreach($reservations as $reservation)
                                @if(!$reservation->food_order_id)
                                    <tr>
                                        <td>R{{ $reservation->id }}</td>
                                        <td>{{ $reservation->restaurant->name }}</td>
                                        <td>Table {{ $reservation->table->number }}</td>
                                        <td>{{ $reservation->nb_personnes ?? '-' }} pers.</td>
                                        <td>{{ \Carbon\Carbon::parse($reservation->date_reservation)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($reservation->status === 'pending')
                                                <span class="badge bg-warning text-dark">En attente</span>
                                            @elseif($reservation->status === 'confirmed')
                                                <span class="badge bg-success">Confirmée</span>
                                            @elseif($reservation->status === 'completed')
                                                <span class="badge bg-primary">Terminée</span>
                                            @else
                                                <span class="badge bg-secondary">Annulée</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-info">Réservation</span></td>
                                        <td>-</td>
                                        <td>
                                            @if(in_array($reservation->status, ['pending','confirmed']))
                                            <form method="POST" action="{{ route('reservations.cancel', $reservation) }}" onsubmit="return confirm('Annuler cette réservation ?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Annuler</button>
                                            </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
    {{-- Vue pour les restaurateurs --}}
    @else
        @if($orders->isEmpty())
            <div class="alert alert-info">
                Aucune commande à traiter.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Client</th>
                            <th>Table</th>
                            <th>Personnes</th>
                            <th>Date et heure</th>
                            <th>Statut</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->client->name }}</td>
                                <td>
                                    @if($order->reservation && $order->reservation->table)
                                        Table {{ $order->reservation->table->number }}
                                        <small class="text-muted d-block">({{ $order->reservation->table->capacity }} places)</small>
                                    @else
                                        Non assignée
                                    @endif
                                </td>
                                <td>{{ $order->reservation->nb_personnes ?? '-' }} pers.</td>
                                <td>
                                    @if($order->reservation && $order->reservation->date_reservation)
                                        {{ \Carbon\Carbon::parse($order->reservation->date_reservation)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                    @if($order->special_requests)
                                        <i class="bi bi-info-circle text-info" title="{{ $order->special_requests }}"></i>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->status_color }}">
                                        {{ $order->status_text }}
                                    </span>
                                </td>
                                <td>{{ number_format($order->total_price, 2) }} €</td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">
                                        Détails
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
</div>
@endsection
