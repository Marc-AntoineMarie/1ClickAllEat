<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Mon panier</h2>

                    @if($cartItems->isEmpty())
                        <p class="text-gray-600">Votre panier est vide.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($cartItems as $item)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold">{{ $item->restaurant->name }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Le {{ $item->reservation_time->format('d/m/Y à H:i') }} -
                                                {{ $item->guests_count }} {{ $item->guests_count > 1 ? 'personnes' : 'personne' }}
                                            </p>
                                            <p class="text-sm text-gray-600">Table n°{{ $item->table->number }}</p>

                                            @if(!empty($item->selected_items))
                                                <div class="mt-4">
                                                    <h4 class="font-medium mb-2">Menu commandé :</h4>
                                                    <ul class="space-y-2">
                                                        @foreach($item->selected_items as $menuItem)
                                                            @if($menuItem['quantity'] > 0)
                                                                <li class="flex justify-between text-sm">
                                                                    <span>{{ $menuItem['name'] }} x{{ $menuItem['quantity'] }}</span>
                                                                    <span>{{ number_format($menuItem['price'] * $menuItem['quantity'], 2) }} €</span>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @if($item->notes)
                                                <div class="mt-2 text-sm text-gray-600">
                                                    <p class="font-medium">Notes :</p>
                                                    <p>{{ $item->notes }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex flex-col items-end space-y-4">
                                            <p class="text-lg font-semibold">{{ number_format($item->total_price, 2) }} €</p>
                                            <form method="POST" action="{{ route('cart.destroy', $item) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')">
                                                    {{ __('Supprimer') }}
                                                </x-danger-button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-8 border-t pt-6">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-lg font-semibold">Total : {{ number_format($cartItems->sum('total_price'), 2) }} €</p>
                                        <p class="text-sm text-gray-600">{{ $cartItems->count() }} {{ $cartItems->count() > 1 ? 'réservations' : 'réservation' }}</p>
                                    </div>
                                    <a href="{{ route('cart.checkout') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Valider et payer') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
