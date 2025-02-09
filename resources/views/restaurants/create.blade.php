@extends('layout.main')

@section('main')
    <h1>Creation restaurant</h1>

    <a href="{{ route('restaurants.index') }}">Retour à la liste</a>

    <form action="{{ route('restaurants.store') }}" method="POST">
        @csrf
        <label for="nom">Nom : </label>
        <input type="text" id="nom" name="nom" placeholder="Nom">
        <br/>
        <label for="description">Déscription : </label>
        <input type="text" id="description" name="description" placeholder="description">
        <br/>
        <label for="place_max">Place Maximum : </label>
        <input type="int" id="place_max" name="place_max" placeholder="place_max">
        <button type="submit">Envoyer</button>
    </form>
@endsection