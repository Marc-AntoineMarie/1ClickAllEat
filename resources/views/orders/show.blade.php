@extends('layouts.app')

@section('title', 'Commande #' . $order->id)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4">Commande #{{ $order->id }}</h1>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Détails de la commande</h5>
                        <span class="badge bg-{{ $order->status_color }}">{{ $order->status_text }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Restaurant :</strong> {{ $order->restaurant->name }}</p>
                            <p><strong>Client :</strong> {{ $order->client->name }}</p>
                            <p><strong>Date de création :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            @if($order->reservation)
                                <p><strong>Date de réservation :</strong> {{ \Carbon\Carbon::parse($order->reservation->date_reservation)->format('d/m/Y H:i') }}</p>
                                <p><strong>Nombre de personnes :</strong> {{ $order->reservation->nb_personnes }} personnes</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($order->reservation && $order->reservation->table)
                                <p><strong>Table réservée :</strong> Table {{ $order->reservation->table->number }}</p>
                                <p><strong>Capacité de la table :</strong> {{ $order->reservation->table->capacity }} places</p>
                            @endif
                            @if($order->delivery_address)
                                <p><strong>Adresse de livraison :</strong><br>{{ $order->delivery_address }}</p>
                            @endif
                            @if($order->notes)
                                <p><strong>Notes :</strong><br>{{ $order->notes }}</p>
                            @endif
                        </div>
                    </div>

                    <h6>Articles commandés</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Article</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ number_format($item->pivot->price, 2) }} €</td>
                                        <td>{{ $item->pivot->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->pivot->price * $item->pivot->quantity, 2) }} €</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end"><strong>{{ number_format($order->total_price, 2) }} €</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    @if($order->status === 'pending')
                        <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Voulez-vous vraiment supprimer cette commande ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 mb-2">
                                <i class="bi bi-trash"></i> Supprimer la commande
                            </button>
                            <div class="small text-muted">Status brut : {{ $order->status }}</div>
                        </form>
                    @else
                        <span class="text-muted">Aucune action disponible</span>
                    @endif
                    @if(Auth::user()->role->name === 'restaurateur')
                        <form action="{{ route('orders.update', $order) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="status" class="form-label">Changer le statut</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="accepted" {{ $order->status === 'accepted' ? 'selected' : '' }}>Acceptée</option>
                                    <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>En préparation</option>
                                    <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Prête</option>
                                    <option value="delivering" {{ $order->status === 'delivering' ? 'selected' : '' }}>En livraison</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Mettre à jour le statut</button>
                        </form>

                        @endif

                        @can('cancel', $order)
                            <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                    Annuler la commande
                                </button>
                            </form>
                        @endcan
                    {{-- Fin du bloc d'action --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
