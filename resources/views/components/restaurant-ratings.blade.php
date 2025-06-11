@props(['restaurant'])

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Avis des clients</h5>
        <div>
            <span class="badge bg-primary">{{ number_format($restaurant->averageRating(), 1) }}/5</span>
            <small class="text-muted">({{ $restaurant->ratingCount() }} avis)</small>
        </div>
    </div>
    <div class="card-body">
        @auth
            @if(auth()->user()->role->name === 'client')
                @php
                    $userRating = $restaurant->ratings()->where('user_id', auth()->id())->first();
                @endphp

                @if($userRating)
                    <form action="{{ route('restaurants.ratings.update', [$restaurant, $userRating]) }}" method="POST" class="mb-4">
                        @csrf
                        @method('PUT')
                        <h6>Modifier votre avis</h6>
                @else
                    <form action="{{ route('restaurants.ratings.store', $restaurant) }}" method="POST" class="mb-4">
                        @csrf
                        <h6>Donner votre avis</h6>
                @endif

                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <div class="rating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="score" value="{{ $i }}" class="btn-check" id="rating{{ $i }}"
                                {{ old('score', $userRating->score ?? '') == $i ? 'checked' : '' }}>
                            <label class="btn btn-outline-warning" for="rating{{ $i }}">{{ $i }} ★</label>
                        @endfor
                    </div>
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">Commentaire (optionnel)</label>
                    <textarea name="comment" id="comment" rows="3" class="form-control">{{ old('comment', $userRating->comment ?? '') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ $userRating ? 'Mettre à jour' : 'Envoyer' }}
                    </button>

                    @if($userRating)
                        <form action="{{ route('restaurants.ratings.destroy', [$restaurant, $userRating]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre avis ?')">
                                Supprimer
                            </button>
                        </form>
                    @endif
                </div>
            </form>
            @endif
        @endauth

        <div class="ratings-list">
            @forelse($restaurant->ratings()->with('user')->latest()->get() as $rating)
                <div class="border-bottom mb-3 pb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating->score)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            <strong>{{ $rating->user->name }}</strong>
                        </div>
                        <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                    </div>
                    @if($rating->comment)
                        <p class="mt-2 mb-0">{{ $rating->comment }}</p>
                    @endif
                </div>
            @empty
                <p class="text-muted">Aucun avis pour le moment.</p>
            @endforelse
        </div>
    </div>
</div>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    gap: 0.5rem;
    justify-content: flex-end;
}

.rating input[type="radio"] {
    display: none;
}

.rating label {
    cursor: pointer;
}

.rating label:hover,
.rating label:hover ~ label,
.rating input[type="radio"]:checked ~ label {
    background-color: #ffc107;
    border-color: #ffc107;
    color: white;
}
</style>
