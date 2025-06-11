@extends('layouts.app')
@section('title', 'Accueil')
@push('styles')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 40px;
        }
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .restaurant-img {
            height: 200px;
            object-fit: cover;
        }
        .restaurant-category {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .rating {
            color: #ffc107;
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 40px 0;
            margin-top: 40px;
        }
        .section-title {
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: #dc3545;
        }
        .btn-primary {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-primary:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>

@endpush


@section('content')
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Commandez en ligne facilement</h1>
            <p class="lead mb-5">Découvrez les meilleurs restaurants près de chez vous et commandez en quelques clics.</p>
            <a href="{{ route('restaurants.index') }}" class="btn btn-danger btn-lg px-5 py-3 fw-bold">Voir tous les restaurants</a>
        </div>
    </section>

    <!-- Featured Restaurants Section -->
    <section class="container my-5">
        <h2 class="section-title">Restaurants populaires</h2>
        <div class="row">
            @if(isset($restaurants) && $restaurants->count() > 0)
                @foreach($restaurants->sortByDesc('ratings_avg_score')->take(6) as $restaurant)
                    <div class="col-md-4">
                        <div class="card h-100">
                            @if(!empty($restaurant->image))
                                <img src="{{ asset('storage/' . $restaurant->image) }}" class="card-img-top restaurant-img" alt="{{ $restaurant->name ?? 'Restaurant' }}">
                            @else
                                <div class="card-img-top restaurant-img d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-utensils fa-3x text-secondary"></i>
                                </div>
                            @endif
                            @if(!empty($restaurant->category))
                                <span class="restaurant-category">{{ $restaurant->category }}</span>
                            @endif
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title">{{ $restaurant->name }}</h5>
                                    @if(isset($restaurant->ratings_count) && $restaurant->ratings_count > 0)
                                        <div>
                                            <span class="rating"><i class="fas fa-star"></i> {{ number_format($restaurant->ratings_avg_score, 1) }}</span>
                                            <small class="text-muted">({{ $restaurant->ratings_count }})</small>
                                        </div>
                                    @endif
                                </div>
                                <p class="card-text"><i class="fas fa-map-marker-alt me-2"></i>{{ $restaurant->adresse ?? 'Adresse non renseignée' }}</p>
                                @if(!empty($restaurant->opening_hours))
                                    <p class="card-text"><i class="fas fa-clock me-2"></i>
                                        @if(is_array($restaurant->opening_hours))
                                            {{ implode(' | ', $restaurant->opening_hours) }}
                                        @else
                                            {{ $restaurant->opening_hours }}
                                        @endif
                                    </p>
                                @endif
                                <hr>
                                <div class="ratings">
                                    @if($restaurant->ratings_count > 0)
                                        @foreach($restaurant->ratings->take(2) as $rating)
                                            <div class="mb-2">
                                                <span>
                                                    @for($i = 0; $i < $rating->score; $i++)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @endfor
                                                    @for($i = $rating->score; $i < 5; $i++)
                                                        <i class="far fa-star text-warning"></i>
                                                    @endfor
                                                </span>
                                                <small class="text-muted">par {{ $rating->user->name ?? 'Anonyme' }} le {{ $rating->created_at ? $rating->created_at->format('d/m/Y') : '' }}</small>
                                                <div>{{ $rating->comment }}</div>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Aucun avis pour ce restaurant</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 pb-3">
                                <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-primary w-100">Voir le menu</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center py-5">
                    <i class="fas fa-utensils fa-3x text-secondary mb-3"></i>
                    <h3>Aucun restaurant disponible pour le moment</h3>
                    <p>Revenez bientôt pour découvrir notre sélection de restaurants.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Comment ça marche</h2>
            <div class="row text-center">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="p-4">
                        <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                            <i class="fas fa-search fa-2x text-danger"></i>
                        </div>
                        <h4>1. Trouvez un restaurant</h4>
                        <p class="text-muted">Parcourez notre sélection de restaurants et trouvez celui qui vous fait envie.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="p-4">
                        <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                            <i class="fas fa-utensils fa-2x text-danger"></i>
                        </div>
                        <h4>2. Choisissez vos plats</h4>
                        <p class="text-muted">Sélectionnez les plats qui vous plaisent et ajoutez-les à votre panier.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4">
                        <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                            <i class="fas fa-truck fa-2x text-danger"></i>
                        </div>
                        <h4>3. Recevez votre commande</h4>
                        <p class="text-muted">Payez en ligne et recevez votre commande directement chez vous.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="container my-5">
        <h2 class="section-title text-center mb-5">Ce que disent nos clients</h2>
        <div class="row">
            @php
                // Récupérer tous les avis positifs (score >= 4) de tous les restaurants
                $allPositiveRatings = collect();
                if(isset($restaurants)) {
                    foreach($restaurants as $restaurant) {
                        if(isset($restaurant->ratings)) {
                            foreach($restaurant->ratings as $rating) {
                                if($rating->score >= 4) {
                                    $allPositiveRatings->push($rating);
                                }
                            }
                        }
                    }
                }
                // Trier par score décroissant puis date la plus récente
                $allPositiveRatings = $allPositiveRatings->sortByDesc(function($rating) {
                    return [$rating->score, $rating->created_at];
                })->take(3);
            @endphp
            @forelse($allPositiveRatings as $rating)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                @for($i = 0; $i < $rating->score; $i++)
                                    <i class="fas fa-star text-warning"></i>
                                @endfor
                                @for($i = $rating->score; $i < 5; $i++)
                                    <i class="far fa-star text-warning"></i>
                                @endfor
                            </div>
                            <p class="card-text">"{{ $rating->comment }}"</p>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $rating->user->name ?? 'Client' }}</h6>
                                    <small class="text-muted">Le {{ $rating->created_at ? $rating->created_at->format('d/m/Y') : '' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-smile-beam fa-3x text-secondary mb-3"></i>
                    <h3>Aucun avis client positif pour le moment</h3>
                </div>
            @endforelse
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-danger text-white py-5 text-center">
        <div class="container">
            <h2 class="mb-4">Prêt à commander ?</h2>
            <p class="lead mb-4">Rejoignez des milliers de clients satisfaits et commandez dès maintenant !</p>
            <a href="{{ route('restaurants.index') }}" class="btn btn-light btn-lg px-5">Voir les restaurants</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="text-uppercase mb-4">1Click<span class="text-danger">AllEat</span></h5>
                    <p>La plateforme de livraison de repas qui vous simplifie la vie. Commandez facilement auprès de vos restaurants préférés.</p>
                    <div class="mt-4">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('restaurants.index') }}" class="text-white">Restaurants</a></li>
                        <li class="mb-2"><a href="#" class="text-white">À propos</a></li>
                        <li class="mb-2"><a href="#" class="text-white">Contact</a></li>
                        <li class="mb-2"><a href="#" class="text-white">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Légal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white">Conditions d'utilisation</a></li>
                        <li class="mb-2"><a href="#" class="text-white">Politique de confidentialité</a></li>
                        <li class="mb-2"><a href="#" class="text-white">Mentions légales</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5 class="text-uppercase mb-4">Contact</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Rue de la Livraison, 75000 Paris</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +33 1 23 45 67 89</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> contact@1clickalleat.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="row align-items-center">
                <div class="col-md-7 col-lg-8">
                    <p class="mb-md-0"> 2023 1ClickAllEat. Tous droits réservés.</p>
                </div>
                <div class="col-md-5 col-lg-4 text-md-end">
                    <p class="mb-0">Conçu avec <i class="fas fa-heart text-danger"></i> pour les gourmands</p>
                </div>
            </div>
        </div>
    </footer>


@endsection
