@extends('layouts.app')

@section('title', 'Restaurants')

@section('content')

<!-- Hero Section -->
<section class="hero-section text-center py-5 bg-light mb-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Découvrez nos restaurants partenaires</h1>
        <p class="lead mb-4">Trouvez et commandez auprès des meilleurs restaurants près de chez vous.</p>
        <a href="#restaurants-list" class="btn btn-danger btn-lg px-5">Explorer</a>
    </div>
</section>

<div class="container">
    <!-- Filtres -->
    <form method="GET" class="row g-3 align-items-end mb-4" action="{{ route('restaurants.index') }}">
        <div class="col-md-3">
            <label for="name" class="form-label">Nom du restaurant</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Nom du restaurant" value="{{ request('name') }}">
        </div>
        <div class="col-md-2">
            <label for="min_rating" class="form-label">Note minimale</label>
            <select name="min_rating" id="min_rating" class="form-select">
                <option value="">Aucune</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('min_rating') == $i ? 'selected' : '' }}>{{ $i }} étoiles et +</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label for="adresse" class="form-label">Adresse / Ville</label>
            <input type="text" name="adresse" id="adresse" class="form-control" placeholder="Ex: Paris" value="{{ request('adresse') }}">
        </div>
        <div class="col-md-2">
            <label for="sort" class="form-label">Trier par</label>
            <select name="sort" id="sort" class="form-select">
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A-Z</option>
                <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Mieux notés</option>
                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récents</option>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-danger">Filtrer</button>
        </div>
    </form>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 id="restaurants-list" class="fw-bold">Restaurants</h2>
        @auth
            @if(Auth::user()->role && Auth::user()->role->name === 'restaurateur')
                <a href="{{ route('restaurants.create') }}" class="btn btn-primary">
                    Ajouter un restaurant
                </a>
            @endif
        @endauth
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($restaurants as $restaurant)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if(!empty($restaurant->image))
                        <img src="{{ asset('storage/' . $restaurant->image) }}" class="card-img-top restaurant-img" alt="{{ $restaurant->name }}">
                    @else
                        <div class="card-img-top restaurant-img d-flex align-items-center justify-content-center bg-light" style="height:200px;">
                            <i class="fas fa-utensils fa-3x text-secondary"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">{{ $restaurant->name }}</h5>
                            @if(!empty($restaurant->category))
                                <span class="badge bg-danger">{{ $restaurant->category }}</span>
                            @endif
                        </div>
                        <p class="card-text text-muted mb-1"><i class="fas fa-map-marker-alt me-1"></i> {{ $restaurant->adresse ?? 'Adresse non renseignée' }}</p>
                        @if(!empty($restaurant->opening_hours))
                            <p class="card-text mb-1"><i class="fas fa-clock me-1"></i> {{ is_array($restaurant->opening_hours) ? implode(' | ', $restaurant->opening_hours) : $restaurant->opening_hours }}</p>
                        @endif
                        <p class="card-text">{{ Str::limit($restaurant->description, 80) }}</p>
                        @if(isset($restaurant->ratings_avg_score))
                            <div class="mb-2">
                                <span class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($restaurant->ratings_avg_score))
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </span>
                                <small class="text-muted">({{ number_format($restaurant->ratings_avg_score, 1) }}/5, {{ $restaurant->ratings_count ?? 0 }} avis)</small>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-danger w-100">Voir le menu</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-utensils fa-3x text-secondary mb-3"></i>
                <h3>Aucun restaurant disponible pour le moment</h3>
                <p>Revenez bientôt pour découvrir notre sélection de restaurants.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
