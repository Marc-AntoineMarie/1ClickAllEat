@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif
@if(
    $errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif
<style>
    body { font-family: 'Figtree', 'Segoe UI', Arial, sans-serif; }
    .restaurant-header-min {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 2.5rem;
        margin-bottom: 1.5rem;
    }
    .restaurant-header-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 16px #dc354510;
        margin-bottom: 1.2rem;
        background: #f8d7da;
    }
    .restaurant-header-title {
        font-size: 2.1rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 0.3rem;
        letter-spacing: 1px;
        text-align: center;
    }
    .restaurant-header-badges {
        display: flex;
        gap: 0.7rem;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 0.7rem;
    }
    .restaurant-header-badges .badge {
        background: #fff;
        color: #dc3545;
        border: 1px solid #f8d7da;
        font-size: 1.01rem;
        font-weight: 500;
        padding: 0.48em 0.9em;
        border-radius: 1.2em;
        display: flex;
        align-items: center;
        gap: 0.3em;
    }
    .restaurant-header-desc {
        color: #444;
        font-size: 1.05rem;
        margin-bottom: 1.4rem;
        text-align: center;
        max-width: 540px;
    }
    .note-etoile {
        color: #ffc107;
        font-size: 1.1rem;
        margin-right: 0.08rem;
    }
    .avis-section {
        margin: 2.2rem 0 1.2rem 0;
    }
    .avis-section h2 {
        font-size: 1.25rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: #dc3545;
        margin-bottom: 0.8rem;
    }
    .avis-tri {
        margin-bottom: 1rem;
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    .avis-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #f8d7da;
        box-shadow: 0 2px 8px #dc354510;
        padding: 1.1rem 1.2rem 0.7rem 1.2rem;
        margin-bottom: 1.2rem;
    }
    .avis-card .avis-header {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        margin-bottom: 0.3rem;
    }
    .avis-card .avis-user {
        font-weight: 600;
        color: #b71c1c;
        font-size: 1.04rem;
    }
    .avis-card .avis-date {
        color: #888;
        font-size: 0.92rem;
    }
    .avis-card .avis-stars {
        color: #ffc107;
        font-size: 1.1rem;
    }
    .avis-card .avis-comment {
        color: #333;
        font-size: 1.03rem;
        margin-top: 0.3rem;
    }
    .menu-card, .carte-card, .table-card {
        border-radius: 1rem;
        box-shadow: 0 2px 16px 0 #dc354510;
        border: 1.2px solid #f8d7da;
        margin-bottom: 1.5rem;
        background: #fff;
    }
    .menu-card h3, .carte-card h3 {
        color: #dc3545;
        font-weight: 600;
    }
    .table-card {
        transition: box-shadow 0.15s;
    }
    .table-card:hover {
        box-shadow: 0 4px 24px 0 #dc354530;
        border-color: #dc3545;
    }
    @media (max-width: 700px) {
        .restaurant-header-min { margin-top: 1.3rem; }
        .restaurant-header-img { width: 80px; height: 80px; }
        .restaurant-header-title { font-size: 1.25rem; }
    }
</style>
<div class="container px-1 px-md-3">
    <div class="restaurant-header-min">
        <img src="https://placehold.co/240x240" alt="{{ $restaurant->name }}" class="restaurant-header-img">
        <div class="restaurant-header-title">{{ $restaurant->name }}</div>
        <div class="restaurant-header-badges">
            @if($restaurant->adresse)
                <span class="badge"><i class="fas fa-map-marker-alt"></i> {{ $restaurant->adresse }}</span>
            @else
                <span class="badge"><i class="fas fa-map-marker-alt"></i> Adresse non renseignée</span>
            @endif
            @if($restaurant->opening_hours)
                @php
                    $horaires = $restaurant->opening_hours;
                    if(is_string($horaires)) {
                        $horaires = json_decode($horaires, true) ?: $restaurant->opening_hours;
                    }
                @endphp
                @if(is_array($horaires))
                    <span class="badge"><i class="far fa-clock"></i> Voir horaires</span>
                @else
                    <span class="badge"><i class="far fa-clock"></i> {{ $horaires }}</span>
                @endif
            @endif
            @if($restaurant->ratingCount() > 0)
                <span class="badge">
                    <i class="fas fa-star note-etoile" style="color:#ffc107;"></i>
                    {{ number_format($restaurant->averageRating(), 1) }} / 5
                </span>
            @endif
        </div>
        @if($restaurant->description)
        <div class="restaurant-header-desc">{{ $restaurant->description }}</div>
        @endif
        @php
            $horaires = $restaurant->opening_hours;
            $horairesArray = null;
            if(is_string($horaires)) {
                $horairesArray = json_decode($horaires, true);
            }
        @endphp
        @php
            $horaires = $restaurant->opening_hours;
            $openHours = $restaurant->openHours ?? null;
            $horairesArray = null;
            if(is_string($horaires)) {
                $horairesArray = json_decode($horaires, true);
            }
        @endphp
        @if($horairesArray && is_array($horairesArray))
        <div class="mt-2 mb-3" style="max-width:350px;margin:auto;">
            <div class="fw-bold mb-1"><i class="far fa-clock"></i> Horaires</div>
            <ul class="list-unstyled mb-0" style="font-size:1.04rem;">
                @foreach($horairesArray as $jour => $plage)
                    <li><span class="fw-semibold">{{ ucfirst($jour) }}</span> : {{ $plage }}</li>
                @endforeach
            </ul>
        </div>
        @elseif(!empty($horaires))
        <div class="mt-2 mb-3" style="max-width:350px;margin:auto;">
            <div class="fw-bold mb-1"><i class="far fa-clock"></i> Horaires</div>
            <div style="font-size:1.04rem;">{{ $horaires }}</div>
        </div>
        @elseif(!empty($openHours))
        <div class="mt-2 mb-3" style="max-width:350px;margin:auto;">
            <div class="fw-bold mb-1"><i class="far fa-clock"></i> Horaires</div>
            <div style="font-size:1.04rem;">{{ $openHours }}</div>
        </div>
        @else
        <div class="mt-2 mb-3" style="max-width:350px;margin:auto;">
            <div class="fw-bold mb-1"><i class="far fa-clock"></i> Horaires</div>
            <div style="font-size:1.04rem;">Horaires non renseignés</div>
        </div>
        @endif
    </div>
    <div class="restaurant-glass-card mx-auto">
        <!-- Avis clients -->
        @php
            $sort = request('sort', 'recent');
            $ratings = $restaurant->ratings;
            if ($sort === 'recent') {
                $ratings = $ratings->sortByDesc('created_at');
            } elseif ($sort === 'oldest') {
                $ratings = $ratings->sortBy('created_at');
            } elseif ($sort === 'best') {
                $ratings = $ratings->sortByDesc('score');
            } elseif ($sort === 'worst') {
                $ratings = $ratings->sortBy('score');
            }
            $ratings = $ratings->take(3);
        @endphp
        <div class="avis-section">
            <h2>Avis clients</h2>
            <form method="get" class="avis-tri">
                <label for="sort" class="form-label mb-0">Trier par :</label>
                <select name="sort" id="sort" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="recent" @if($sort=='recent') selected @endif>Plus récent</option>
                    <option value="oldest" @if($sort=='oldest') selected @endif>Plus ancien</option>
                    <option value="best" @if($sort=='best') selected @endif>Meilleure note</option>
                    <option value="worst" @if($sort=='worst') selected @endif>Moins bonne note</option>
                </select>
            </form>
            @forelse($ratings as $rating)
                <div class="avis-card">
                    <div class="avis-header">
                        <span class="avis-user">{{ $rating->user->name ?? 'Utilisateur' }}</span>
                        <span class="avis-date">{{ $rating->created_at->format('d/m/Y') }}</span>
                        <span class="avis-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $rating->score ? '' : ' text-muted' }}"></i>
                            @endfor
                        </span>
                    </div>
                    @if($rating->comment)
                        <div class="avis-comment">{{ $rating->comment }}</div>
                    @endif
                </div>
            @empty
                <div class="text-muted">Aucun avis pour ce restaurant.</div>
            @endforelse
        </div>
        <!-- Menus du jour -->

                
                <div class="ml-6">
                    <span class="text-gray-600">
                        @if($restaurant->opening_hours)
                            {{ $restaurant->opening_hours }}
                        @else
                            Horaires non disponibles
                        @endif
                    </span>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <h2 class="text-xl font-semibold mb-4">Menus du jour</h2>
                @php
                    $menusDuJour = ($restaurant->menus ?? collect())->where('is_daily', true)->where('date', today()->toDateString());
                    $carte = ($restaurant->menus ?? collect())->where('is_daily', false)->first();
                @endphp
                @if($menusDuJour->count())
                    @foreach($menusDuJour as $menu)
                        <div class="mb-4 border rounded-lg p-4 bg-white">
                            <h3 class="text-lg font-semibold mb-2">{{ $menu->name }} ({{ $menu->date }})
                                @if($menu->promotion)
                                    <span class="ml-2 bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Promo : -{{ $menu->promotion }}%</span>
                                @endif
                            </h3>
                            <ul class="list-disc ml-6">
                                @forelse($menu->items as $item)
                                    <li>
                                        {{ $item->name }}
                                        @if($item->promotion)
                                            <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Promo: -{{ $item->promotion }}%</span>
                                        @endif
                                        @if($item->isSoldOut())
                                            <span class="ml-2 text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Indisponible</span>
                                        @endif
                                        <span class="ml-2 text-gray-700">{{ $item->effective_price }} €</span>
                                    </li>
                                @empty
                                    <li class="text-gray-500">Aucun plat dans ce menu.</li>
                                @endforelse
                            </ul>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500">Aucun menu du jour pour aujourd'hui.</p>
                @endif

                <h2 class="text-xl font-semibold mb-4 mt-8">Carte</h2>
                @if($carte)
                    <div class="mb-4 border rounded-lg p-4 bg-white">
                        <h3 class="text-lg font-semibold mb-2">{{ $carte->name }}
                            @if($carte->promotion)
                                <span class="ml-2 bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Promo : -{{ $carte->promotion }}%</span>
                            @endif
                        </h3>
                        <ul class="list-disc ml-6">
                            @forelse($carte->items as $item)
                                <li>
                                    {{ $item->name }}
                                    @if($item->promotion)
                                        <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Promo: -{{ $item->promotion }}%</span>
                                    @endif
                                    @if($item->isSoldOut())
                                        <span class="ml-2 text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Indisponible</span>
                                    @endif
                                    <span class="ml-2 text-gray-700">{{ $item->effective_price }} €</span>
                                </li>
                            @empty
                                <li class="text-gray-500">Aucun plat dans la carte.</li>
                            @endforelse
                        </ul>
                    </div>
                @elseif($restaurant->items->count() > 0)
                    <div class="mb-4">
                        <h3 class="text-2xl font-bold mb-4">Carte du restaurant</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach($restaurant->items as $item)
                                <div class="bg-white rounded-lg shadow p-5 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-lg font-semibold">{{ $item->name }}</h4>
                                            @if($item->promotion)
                                                <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Promo: -{{ $item->promotion }}%</span>
                                            @endif
                                            @if($item->isSoldOut())
                                                <span class="ml-2 text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Indisponible</span>
                                            @endif
                                        </div>
                                        <p class="text-gray-600 text-sm mb-2">{{ $item->description }}</p>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-lg font-bold text-primary">{{ $item->effective_price }} €</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-gray-600">Aucune carte n'a encore été créée pour ce restaurant.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Bouton Commander des plats avec réservation -->
    @auth
        @if(auth()->user()->role && auth()->user()->role->name !== 'restaurateur')
        <div class="text-center my-5">
            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#orderModal">
                <i class="fa fa-utensils me-2"></i>Commander & Réserver
            </button>
        </div>
        @endif
    @else
        <div class="text-center my-5">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                <i class="fa fa-utensils me-2"></i>Connectez-vous pour commander
            </a>
        </div>
    @endauth

    <!-- Modale de commande de plats avec réservation -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="orderModalLabel">Commander & Réserver</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
            @csrf
            <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
            <div class="modal-body">
              <div class="mb-4">
                <h5 class="border-bottom pb-2"><i class="fa fa-utensils me-2"></i>Sélection des plats</h5>
                <div class="table-responsive">
                  <table class="table align-middle">
                    <thead>
                      <tr>
                        <th>Plat</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($restaurant->items as $item)
                      <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="item-price" data-price="{{ $item->effective_price }}">{{ $item->effective_price }} €</td>
                        <td>
                          @if($item->isSoldOut())
                            <span class="badge bg-danger">Indisponible</span>
                          @else
                            <input type="number" min="0" max="99" class="form-control quantity-input" name="items[{{ $item->id }}][quantity]" value="0" data-price="{{ $item->effective_price }}">
                          @endif
                        </td>
                        <td class="item-total">0 €</td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="text-end mt-3">
                  <span class="fw-bold fs-5">Total de la commande : <span id="orderTotal">0 €</span></span>
                </div>
              </div>
              
              <div class="mt-5">
                <h5 class="border-bottom pb-2"><i class="fa fa-chair me-2"></i>Réservation de table</h5>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="modal_table_id" class="form-label">Table disponible :</label>
                    <select name="table_id" id="modal_table_id" class="form-select" required>
                      <option value="">Sélectionner une table</option>
                      @foreach($restaurant->tables as $table)
                        @if($table->is_available)
                          <option value="{{ $table->id }}">Table {{ $table->number }} ({{ $table->capacity }} pers.)</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="modal_nb_personnes" class="form-label">Nombre de personnes :</label>
                    <input type="number" min="1" max="20" name="nb_personnes" id="modal_nb_personnes" class="form-control" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="modal_date_reservation" class="form-label">Date :</label>
                    <input type="date" name="date_reservation" id="modal_date_reservation" class="form-control" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="modal_heure_reservation" class="form-label">Heure :</label>
                    <input type="time" name="heure_reservation" id="modal_heure_reservation" class="form-control" required>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-success">Valider la commande & réservation</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        function updateTotals() {
          let total = 0;
          document.querySelectorAll('.quantity-input').forEach(function(input) {
            const qty = parseInt(input.value) || 0;
            const price = parseFloat(input.dataset.price);
            const row = input.closest('tr');
            const rowTotal = qty * price;
            row.querySelector('.item-total').textContent = rowTotal.toFixed(2) + ' €';
            total += rowTotal;
          });
          document.getElementById('orderTotal').textContent = total.toFixed(2) + ' €';
        }
        document.querySelectorAll('.quantity-input').forEach(function(input) {
          input.addEventListener('input', updateTotals);
        });
        updateTotals();
      });
    </script>
</div>
    <!-- Le bouton de réservation a été supprimé car intégré avec la commande -->
@endsection
