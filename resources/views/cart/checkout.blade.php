@extends('layouts.app')

@section('title', 'Confirmer la réservation')

@section('content')
<div class="container">
    <h1 class="mb-4">Confirmer la réservation</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Détails de la réservation</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('cart.checkout') }}" method="POST" id="reservation-form">
                        @csrf
                        <input type="hidden" name="restaurant_id" value="{{ $cart['restaurant_id'] }}">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="reservation_date" class="form-label">Date de réservation</label>
                                <input type="date" class="form-control @error('reservation_date') is-invalid @enderror" 
                                    id="reservation_date" name="reservation_date" min="{{ date('Y-m-d') }}" 
                                    value="{{ old('reservation_date') }}" required>
                                @error('reservation_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="reservation_time" class="form-label">Heure de réservation</label>
                                <input type="time" class="form-control @error('reservation_time') is-invalid @enderror" 
                                    id="reservation_time" name="reservation_time" 
                                    value="{{ old('reservation_time') }}" required>
                                @error('reservation_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="number_of_guests" class="form-label">Nombre de personnes</label>
                                <input type="number" class="form-control @error('number_of_guests') is-invalid @enderror" 
                                    id="number_of_guests" name="number_of_guests" min="1" max="12" 
                                    value="{{ old('number_of_guests', 2) }}" required>
                                @error('number_of_guests')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="table_id" class="form-label">Table</label>
                                <select class="form-select @error('table_id') is-invalid @enderror" 
                                    id="table_id" name="table_id" required>
                                    <option value="">Choisir une table...</option>
                                    @foreach($restaurant->tables->where('is_available', true)->sortBy('capacity') as $table)
                                        <option value="{{ $table->id }}" 
                                            {{ old('table_id') == $table->id ? 'selected' : '' }}
                                            data-capacity="{{ $table->capacity }}">
                                            Table {{ $table->number }} ({{ $table->capacity }} personnes)
                                        </option>
                                    @endforeach
                                </select>
                                @error('table_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="special_requests" class="form-label">Demandes spéciales (optionnel)</label>
                                <textarea class="form-control @error('special_requests') is-invalid @enderror" 
                                    id="special_requests" name="special_requests" rows="3">{{ old('special_requests') }}</textarea>
                                @error('special_requests')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Récapitulatif de la commande</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">{{ $restaurant->name }}</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @foreach($cart['items'] as $item)
                                    <tr>
                                        <td>
                                            {{ $item['name'] }}
                                            <br>
                                            <small class="text-muted">{{ $item['quantity'] }}x</small>
                                        </td>
                                        <td class="text-end">{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td class="text-end"><strong>{{ number_format($cart['total'], 2) }} €</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" form="reservation-form" class="btn btn-primary w-100">
                        Confirmer la réservation
                    </button>

                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        Retour au panier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const guestsInput = document.getElementById('number_of_guests');
    const tableSelect = document.getElementById('table_id');
    const tableOptions = Array.from(tableSelect.options);

    function updateAvailableTables() {
        const guests = parseInt(guestsInput.value) || 0;
        
        tableOptions.forEach(option => {
            if (!option.value) return; // Skip placeholder option
            
            const capacity = parseInt(option.dataset.capacity);
            if (capacity < guests) {
                option.disabled = true;
                option.style.display = 'none';
            } else {
                option.disabled = false;
                option.style.display = '';
            }
        });

        // If current selection is invalid, reset it
        if (tableSelect.selectedOptions[0].disabled) {
            tableSelect.value = '';
        }

        // Show message if no tables available
        const hasAvailableTables = tableOptions.some(option => 
            option.value && !option.disabled);

        if (!hasAvailableTables) {
            tableSelect.innerHTML = '<option value="">Aucune table disponible pour ce nombre de personnes</option>';
        } else if (tableSelect.options.length === 1) {
            // Restore options if they were removed
            tableSelect.innerHTML = '';
            tableOptions.forEach(option => tableSelect.add(option.cloneNode(true)));
        }
    }

    guestsInput.addEventListener('change', updateAvailableTables);
    guestsInput.addEventListener('input', updateAvailableTables);
    updateAvailableTables();
});
</script>
@endpush
