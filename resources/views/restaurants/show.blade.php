@extends('layout.main')

@section('main')

<div class="container mt-4">
    <h1 class="text-center mb-4">Restaurants</h1>
    
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('restaurants.create') }}" class="btn btn-success">Ajouter un Restaurant</a>
    </div>

    <div class="swiper-container">
        <div class="swiper-wrapper">
            @foreach($restaurants->chunk(6) as $restaurantChunk)
                <div class="swiper-slide">
                    <div class="row">
                        @foreach($restaurantChunk as $restaurant)
                            <div class="col-md-4 col-lg-2 mb-4">
                                <div class="card shadow-sm overflow-hidden">
                                    <img src="{{ $restaurant->image ?? 'https://via.placeholder.com/300x200?text=Restaurant' }}" 
                                         class="w-100" alt="Image du restaurant" 
                                         style="height: 150px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title text-truncate">{{ $restaurant->nom }}</h5>
                                        <p class="card-text text-truncate">{{ Str::limit($restaurant->description, 50) }}</p>
                                        <span class="badge {{ $restaurant->place_disponible > 0 ? 'bg-primary' : 'bg-danger' }}">
                                            {{ $restaurant->place_disponible }} / {{ $restaurant->place_max }} Places
                                        </span>
                                        <div class="mt-2 d-flex justify-content-between">
                                            <a href="{{ route('restaurants.index', $restaurant) }}" class="btn btn-info btn-sm">Détails</a>
                                            <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-warning btn-sm">Modifier</a>
                                            <form action="{{ route('restaurants.destroy', $restaurant->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <input type="hidden" name="id" value="{{ $restaurant->id }}">
                                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

@endsection

@section('scripts')

<script>

</script>
@endsection
