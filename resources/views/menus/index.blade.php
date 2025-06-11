@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Menus du restaurant {{ $restaurant->name }}</h1>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="mb-4">
        <a href="{{ route('restaurants.menus.create', $restaurant) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer un nouveau menu</a>
    </div>
    @if($menus->count())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($menus as $menu)
                <div class="border rounded-lg p-4 bg-white">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-semibold">{{ $menu->name }}</h2>
                        <div>
                            <a href="{{ route('restaurants.menus.edit', [$restaurant, $menu]) }}" class="text-blue-600 hover:underline mr-2">Éditer</a>
                            <form action="{{ route('restaurants.menus.destroy', [$restaurant, $menu]) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce menu ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </div>
                    </div>
                    <div class="mb-2">
                        @if($menu->is_daily)
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Menu du jour ({{ $menu->date }})</span>
                        @else
                            <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded text-xs">Carte</span>
                        @endif
                        @if($menu->promotion)
                            <span class="ml-2 bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Promo : -{{ $menu->promotion }}%</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-medium">Plats du menu :</h3>
                        <ul class="list-disc ml-5">
                            @forelse($menu->items as $item)
                                <li>
                                    {{ $item->name }}
                                    @if($item->promotion)
                                        <span class="text-green-600 text-xs">(-{{ $item->promotion }}%)</span>
                                    @endif
                                    @if($item->isSoldOut())
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs ml-2">Indisponible</span>
                                    @endif
                                </li>
                            @empty
                                <li class="text-gray-500">Aucun plat dans ce menu.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">Aucun menu n'a encore été créé pour ce restaurant.</p>
    @endif
</div>
@endsection
