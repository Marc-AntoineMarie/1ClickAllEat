<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Administrateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Bienvenue dans votre espace administrateur') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium mb-2">{{ __('Gestion des Utilisateurs') }}</h4>
                            <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('Gérer les utilisateurs') }}
                            </a>
                        </div>
                        
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium mb-2">{{ __('Restaurants') }}</h4>
                            <a href="{{ route('restaurants.index') }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('Voir tous les restaurants') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
