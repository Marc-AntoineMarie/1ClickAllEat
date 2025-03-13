<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Restaurateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Bienvenue dans votre espace restaurateur') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium mb-2">{{ __('Mes Restaurants') }}</h4>
                            <a href="{{ route('restaurateur.restaurants.index') }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('Gérer mes restaurants') }}
                            </a>
                        </div>
                        
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium mb-2">{{ __('Menus') }}</h4>
                            <a href="{{ route('restaurateur.menus.index') }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('Gérer les menus') }}
                            </a>
                        </div>
                        
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium mb-2">{{ __('Catégories') }}</h4>
                            <a href="{{ route('restaurateur.categories.index') }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('Gérer les catégories') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
