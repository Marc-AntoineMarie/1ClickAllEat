@extends('layout.main')

@section('main')
    <h1>Modifier un restaurant</h1>
    <form action="{{ route('restaurants.update', $restaurant->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Laravel attend une requête PUT pour une mise à jour --}}
        
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="{{ $restaurant->nom }}">

        <label for="description">Description :</label>
        <input type="text" id="description" name="description" value="{{ $restaurant->description }}">

        <label for="place_max">Place Maximum :</label>
        <input type="number" id="place_max" name="place_max" value="{{ $restaurant->place_max }}">

        <button type="submit">Mettre à jour</button>
    </form>
@endsection
