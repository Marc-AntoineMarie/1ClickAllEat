<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord employé') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h3 class="text-lg font-bold mb-4">Vos disponibilités</h3>
                    
                    <!-- Form pour ajouter une disponibilité -->
                    <div class="mb-8 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold mb-3">Ajouter une disponibilité</h4>
                        <form action="{{ route('employee.availability.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            @csrf
                            <div>
                                <label for="restaurant_id" class="block text-sm font-medium text-gray-700">Restaurant</label>
                                <select name="restaurant_id" id="restaurant_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" name="date" id="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700">Heure début</label>
                                <input type="time" name="start_time" id="start_time" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700">Heure fin</label>
                                <input type="time" name="end_time" id="end_time" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Ajouter
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Calendrier des disponibilités -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b">Jour</th>
                                    <th class="py-2 px-4 border-b">Date</th>
                                    <th class="py-2 px-4 border-b">Disponibilités</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dates as $date)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $date['day'] }}</td>
                                        <td class="py-2 px-4 border-b">{{ $date['formatted'] }}</td>
                                        <td class="py-2 px-4 border-b">
                                            @if(isset($availabilities[$date['date']]))
                                                <div class="space-y-2">
                                                    @foreach($availabilities[$date['date']] as $availability)
                                                        <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                                            <div>
                                                                <span class="font-medium">{{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}</span>
                                                                <span class="text-sm text-gray-600 ml-2">{{ $restaurants->find($availability->restaurant_id)->name ?? 'Restaurant' }}</span>
                                                            </div>
                                                            <form action="{{ route('employee.availability.destroy', $availability->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette disponibilité?');">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-500">Aucune disponibilité</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
