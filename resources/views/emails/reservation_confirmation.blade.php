<h1>Confirmation de réservation</h1>
<p>Bonjour {{ $reservation->user->name }},</p>
<p>Votre réservation a bien été enregistrée au restaurant <strong>{{ $reservation->restaurant->name }}</strong>.</p>
<ul>
    <li><strong>Table :</strong> {{ $reservation->table->number }}</li>
    <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($reservation->date_reservation)->format('d/m/Y H:i') }}</li>
    <li><strong>Statut :</strong> {{ ucfirst($reservation->status) }}</li>
</ul>
<p>Merci et à bientôt !</p>
