<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Paiement</h2>

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">Récapitulatif de la commande</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            @foreach ($cartItems as $item)
                                <div class="flex justify-between items-center py-2">
                                    <div>
                                        <span class="font-medium">{{ $item->name }}</span>
                                        <span class="text-gray-600 text-sm ml-2">x{{ $item->quantity }}</span>
                                    </div>
                                    <span>{{ number_format($item->price * $item->quantity, 2) }} €</span>
                                </div>
                            @endforeach
                            <div class="border-t border-gray-200 mt-4 pt-4">
                                <div class="flex justify-between items-center font-semibold">
                                    <span>Total</span>
                                    <span>{{ number_format($total, 2) }} €</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form id="payment-form" action="{{ route('cart.process-payment') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4">Informations de paiement</h3>
                            <div id="card-element" class="bg-white border border-gray-300 rounded-md p-4"></div>
                            <div id="card-errors" class="text-red-600 mt-2"></div>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                            Payer {{ number_format($total, 2) }} €
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe('{{ config('services.stripe.key') }}');
            const elements = stripe.elements();
            const card = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                        fontSmoothing: 'antialiased',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                }
            });

            card.mount('#card-element');

            card.addEventListener('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            const form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        const errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripeToken');
                        hiddenInput.setAttribute('value', result.token.id);
                        form.appendChild(hiddenInput);
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
