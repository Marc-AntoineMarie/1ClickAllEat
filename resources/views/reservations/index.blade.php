@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Mes réservations de table</h1>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
    @endif
    @if($reservations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded shadow">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Restaurant</th>
                        <th class="px-4 py-2">Table</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Statut</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                        <tr>
                            <td class="border px-4 py-2">{{ $reservation->restaurant->name }}</td>
                            <td class="border px-4 py-2">{{ $reservation->table->number }}</td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($reservation->date_reservation)->format('d/m/Y H:i') }}</td>
                            <td class="border px-4 py-2">
                                @if($reservation->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">En attente</span>
                                @elseif($reservation->status === 'confirmed')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Confirmée</span>
                                @elseif($reservation->status === 'completed')
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Terminée</span>
                                @else
                                    <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded">Annulée</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                @if(in_array($reservation->status, ['pending','confirmed']))
                                <form method="POST" action="{{ route('reservations.cancel', $reservation) }}" onsubmit="return confirm('Annuler cette réservation ?');">
                                    @csrf
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Annuler</button>
                                </form>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600">Vous n'avez aucune réservation pour le moment.</p>
    @endif
</div>
@endsection
