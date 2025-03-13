<x-app-layout>
    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold">Réserver une table chez {{ $restaurant->name }}</h2>
                        <p class="text-gray-600 mt-1">{{ $restaurant->address }}</p>
                    </div>

                    <form method="POST" action="{{ route('reservations.store', ['restaurant' => $restaurant]) }}" class="space-y-6">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="guests_count" value="Nombre de convives" />
                                <x-text-input id="guests_count" name="guests_count" type="number" min="1" max="20" class="mt-1 block w-full" value="{{ old('guests_count') }}" required />
                                <p class="mt-1 text-sm text-gray-500">Nombre maximum de convives : 20</p>
                            </div>

                            <div>
                                <x-input-label for="reservation_date" value="Date" />
                                <x-text-input id="reservation_date" name="reservation_date" type="date" class="mt-1 block w-full" min="{{ date('Y-m-d') }}" value="{{ old('reservation_date') }}" required />
                            </div>

                            <div>
                                <x-input-label for="reservation_time" value="Heure" />
                                <select id="reservation_time" name="reservation_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Sélectionnez une heure</option>
                                    @for ($hour = 11; $hour <= 22; $hour++)
                                        @foreach (['00', '30'] as $minute)
                                            @php
                                                $time = sprintf('%02d:%s', $hour, $minute);
                                            @endphp
                                            <option value="{{ $time }}" {{ old('reservation_time') == $time ? 'selected' : '' }}>
                                                {{ $time }}
                                            </option>
                                        @endforeach
                                    @endfor
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Horaires de réservation : 11h00 - 22h30</p>
                            </div>

                            <div>
                                <x-input-label for="notes" value="Notes spéciales (allergies, préférences...)" />
                                <textarea id="notes" name="notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>
                                {{ __('Réserver') }}
                            </x-primary-button>

                            <a href="{{ route('restaurants.show', ['restaurant' => $restaurant]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Annuler') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('reservation_date');
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            // Format tomorrow's date as YYYY-MM-DD
            const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
            dateInput.min = tomorrowFormatted;

            // Set default value to tomorrow if no date is selected
            if (!dateInput.value) {
                dateInput.value = tomorrowFormatted;
            }
        });
    </script>
    @endpush
</x-app-layout>
