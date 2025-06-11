@extends('layouts.app')

@section('title', 'Mon panier')

@section('content')
<div class="container">
    <h1 class="mb-4">Mon panier</h1>

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

    @if(empty($cart['items']))
        <div class="alert alert-info">
            Votre panier est vide.
            <a href="{{ route('restaurants.index') }}" class="alert-link">Parcourir les restaurants</a>
        </div>
    @else
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Articles commandés</h5>
                    @if($restaurant)
                        <span class="text-muted">{{ $restaurant->name }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Prix unitaire</th>
                                <th style="width: 200px;">Quantité</th>
                                <th class="text-end">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart['items'] as $id => $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ number_format($item['price'], 2) }} €</td>
                                    <td>
                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                                min="1" max="10" class="form-control" style="width: 80px;">
                                            <button type="submit" class="btn btn-outline-primary">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                                    <td class="text-end">
                                        <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                <td class="text-end"><strong>{{ number_format($cart['total'], 2) }} €</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" 
                            onclick="return confirm('Êtes-vous sûr de vouloir vider votre panier ?')">
                            Vider le panier
                        </button>
                    </form>
                    <a href="{{ route('cart.showCheckout') }}" class="btn btn-primary">
                        Réserver une table
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
