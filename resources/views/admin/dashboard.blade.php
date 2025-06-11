@extends('layouts.app')

@section('title', 'Dashboard Administrateur')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Dashboard Administrateur</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Utilisateurs</h6>
                    <h3 class="card-text mb-0">{{ $stats['users']->total_users }}</h3>
                    <small>
                        {{ $stats['users']->total_clients }} clients,
                        {{ $stats['users']->total_restaurateurs }} restaurateurs
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Restaurants</h6>
                    <h3 class="card-text mb-0">{{ $stats['restaurants']->total_restaurants }}</h3>
                    <small>+{{ $stats['restaurants']->month_restaurants }} ce mois</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h6 class="card-title">Commandes</h6>
                    <h3 class="card-text mb-0">{{ $stats['orders']->total_orders }}</h3>
                    <small>{{ $stats['orders']->today_orders }} aujourd'hui</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6 class="card-title">CA Total</h6>
                    <h3 class="card-text mb-0">{{ number_format($stats['revenue']->total_revenue ?? 0, 2) }} €</h3>
                    <small>{{ number_format($stats['revenue']->today_revenue ?? 0, 2) }} € aujourd'hui</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Derniers restaurants</h5>
                    <a href="{{ route('admin.restaurants') }}" class="btn btn-sm btn-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Restaurant</th>
                                    <th>Propriétaire</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestRestaurants as $restaurant)
                                    <tr>
                                        <td>
                                            <a href="{{ route('restaurants.show', $restaurant) }}">
                                                {{ $restaurant->name }}
                                            </a>
                                        </td>
                                        <td>{{ $restaurant->owner->name }}</td>
                                        <td>{{ $restaurant->created_at->format('d/m/Y') }}</td>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Derniers utilisateurs</h5>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role->name === 'admin' ? 'danger' : ($user->role->name === 'restaurateur' ? 'success' : 'primary') }}">
                                                {{ ucfirst($user->role->name) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Dernières commandes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Client</th>
                                    <th>Restaurant</th>
                                    <th>Total</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->client->name }}</td>
                                        <td>{{ $order->restaurant->name }}</td>
                                        <td>{{ number_format($order->total, 2) }} €</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_color }}">
                                                {{ $order->status_text }}
                                            </span>
                                        </td>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Derniers avis</h5>
                    <a href="{{ route('admin.ratings') }}" class="btn btn-sm btn-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Restaurant</th>
                                    <th>Client</th>
                                    <th>Note</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestRatings as $rating)
                                    <tr>
                                        <td>{{ $rating->restaurant->name }}</td>
                                        <td>{{ $rating->user->name }}</td>
                                        <td>
                                            <span class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $rating->rating ? '-fill' : '' }}"></i>
                                                @endfor
                                            </span>
                                        </td>
                                        <td>{{ $rating->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
