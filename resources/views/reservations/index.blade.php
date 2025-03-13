<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Mes réservations</h2>

                    @if($reservations->isEmpty())
                        <p class="text-gray-600">Vous n'avez pas encore de réservations.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($reservations as $reservation)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold">{{ $reservation->restaurant->name }}</h3>
                                            <p class="text-gray-600">
                                                {{ $reservation->reservation_time->format('d/m/Y H:i') }} -
                                                {{ $reservation->guests_count }} {{ $reservation->guests_count > 1 ? 'personnes' : 'personne' }}
                                            </p>
                                            <p class="text-sm text-gray-500">Table n°{{ $reservation->table->number }}</p>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="px-2 py-1 text-sm rounded-full {{ $reservation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                            
                                            <a href="{{ route('reservations.show', ['reservation' => $reservation]) }}">
                                                <x-secondary-button>
                                                    {{ __('Détails') }}
                                                </x-secondary-button>
                                            </a>

                                            @if($reservation->status !== 'cancelled')
                                                <form method="POST" action="{{ route('reservations.cancel', ['reservation' => $reservation]) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <x-danger-button onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                                        {{ __('Annuler') }}
                                                    </x-danger-button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $reservations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
