@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Modifier le menu : {{ $menu->name }}</h1>
    <form method="POST" action="{{ route('restaurants.menus.update', [$restaurant, $menu]) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1 font-medium">Nom du menu</label>
            <input type="text" name="name" value="{{ old('name', $menu->name) }}" class="border rounded px-3 py-2 w-full" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Type</label>
            <select name="is_daily" class="border rounded px-3 py-2 w-full">
                <option value="0" @if(!$menu->is_daily) selected @endif>Carte</option>
                <option value="1" @if($menu->is_daily) selected @endif>Menu du jour</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Date (si menu du jour)</label>
            <input type="date" name="date" value="{{ old('date', $menu->date) }}" class="border rounded px-3 py-2 w-full">
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Promotion (%)</label>
            <input type="number" name="promotion" min="0" max="100" step="0.01" value="{{ old('promotion', $menu->promotion) }}" class="border rounded px-3 py-2 w-full">
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Plats du menu</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                @foreach($items as $item)
                    <label class="flex items-center">
                        <input type="checkbox" name="items[]" value="{{ $item->id }}" class="mr-2" @if($menu->items->contains($item)) checked @endif>
                        {{ $item->name }}
                        @if($item->isSoldOut())
                            <span class="ml-2 text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Indisponible</span>
                        @endif
                        @if($item->promotion)
                            <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Promo: -{{ $item->promotion }}%</span>
                        @endif
                    </label>
                @endforeach
            </div>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
        <a href="{{ route('restaurants.menus.index', $restaurant) }}" class="ml-3 text-gray-600 hover:underline">Annuler</a>
    </form>
</div>
@endsection
