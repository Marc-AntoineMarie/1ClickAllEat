<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Détails de la réservation</h2>
                        @if($reservation->status !== 'cancelled')
                            <form method="POST" action="{{ route('reservations.cancel', $reservation) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <x-danger-button onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                    {{ __('Annuler la réservation') }}
                                </x-danger-button>
                            </form>
                        @endif
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                        <div>
                            <span class="font-semibold">Restaurant :</span>
                            <span>{{ $reservation->restaurant->name }}</span>
                        </div>

                        <div>
                            <span class="font-semibold">Date et heure :</span>
                            <span>{{ $reservation->reservation_time->format('d/m/Y H:i') }}</span>
                        </div>

                        <div>
                            <span class="font-semibold">Nombre de convives :</span>
                            <span>{{ $reservation->guests_count }}</span>
                        </div>

                        <div>
                            <span class="font-semibold">Numéro de table :</span>
                            <span>{{ $reservation->table->number }}</span>
                        </div>

                        <div>
                            <span class="font-semibold">Statut :</span>
                            <span class="px-2 py-1 text-sm rounded-full {{ $reservation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>

                        @if($reservation->notes)
                            <div>
                                <span class="font-semibold">Notes spéciales :</span>
                                <p class="mt-1">{{ $reservation->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('reservations.index') }}">
                            <x-secondary-button>
                                {{ __('Retour à mes réservations') }}
                            </x-secondary-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
