@extends('layout.main')

@section('main')
    <h1>Restaurants</h1>

    <a href="{{ route('restaurants.create') }}">Créer un restaurant</a>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($restaurants as $restaurant)
                <tr>
                    <td>{{ $restaurant->id }}</td>
                    <td>{{ $restaurant->name }}</td>
                    <td>
                        <div style="display: flex;">
                            <a style="margin-right: 8px;" href="{{ route('restaurants.show', $restaurant->id) }}">Voir</a>
                            <a style="margin-right: 8px;" href="{{ route('restaurants.edit', $restaurant->id) }}">Modifier</a>
                            <form action="{{ route('restaurants.destroy', $restaurant->id) }}" method="POST">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="id" value="{{ $restaurant->id }}">
                                <button type="submit">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    <script>
        console.log("scripts !");
    </script>
@endsection