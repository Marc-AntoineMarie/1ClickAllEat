@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Plats de {{ $restaurant->name }}</h1>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Nom</th>
                <th class="px-4 py-2">Catégorie</th>
                <th class="px-4 py-2">Prix</th>
                <th class="px-4 py-2">Promotion</th>
                <th class="px-4 py-2">Disponibilité</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td class="border px-4 py-2">{{ $item->name }}</td>
                    <td class="border px-4 py-2">{{ $item->category->name ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $item->prix }} €</td>
                    <td class="border px-4 py-2">
                        @if($item->promotion)
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded">-{{ $item->promotion }}%</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        @if($item->isSoldOut())
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded">Indisponible</span>
                        @else
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Disponible</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        <form action="{{ route('restaurants.items.toggle_disponibility', [$restaurant, $item]) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded @if($item->isSoldOut()) bg-green-600 text-white @else bg-red-600 text-white @endif">
                                @if($item->isSoldOut())
                                    Rendre disponible
                                @else
                                    Marquer indisponible
                                @endif
                            </button>
                        </form>
                        <a href="{{ route('items.edit', [$restaurant, $item]) }}" class="ml-2 text-blue-600 hover:underline">Éditer</a>
                        <form action="{{ route('items.destroy', [$restaurant, $item]) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Supprimer ce plat ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
