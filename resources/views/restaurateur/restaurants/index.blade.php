<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mes Restaurants') }}
            </h2>
            <a href="{{ route('restaurateur.restaurants.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Ajouter un restaurant
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($restaurants->isEmpty())
                        <p class="text-center text-gray-500">Vous n'avez pas encore de restaurant. Commencez par en créer un !</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($restaurants as $restaurant)
                                <div class="border rounded-lg p-4 shadow-sm">
                                    <h3 class="text-lg font-semibold mb-2">{{ $restaurant->name }}</h3>
                                    <p class="text-gray-600 mb-2">{{ $restaurant->address }}</p>
                                    <p class="text-gray-600 mb-4">{{ $restaurant->phone }}</p>
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('restaurateur.restaurants.edit', $restaurant) }}" class="text-blue-600 hover:text-blue-800">
                                            Modifier
                                        </a>
                                        <form action="{{ route('restaurateur.restaurants.destroy', $restaurant) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce restaurant ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
