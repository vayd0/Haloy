<h1>Profil de {{ $user->name }}</h1>

<div class="stats">
    <p><strong>{{ $user->suiveurs->count() }}</strong> followers</p>
    <p><strong>{{ $user->suivis->count() }}</strong> abonnements</p>
</div>

@auth
    @if(auth()->id() !== $user->id)
        <form action="{{ route('users.follow', $user) }}" method="POST">
            @csrf
            <button type="submit">
                {{ auth()->user()->suivis->contains($user->id) ? 'Ne plus suivre' : 'Suivre' }}
            </button>
        </form>
    @endif
@endauth

<section>
    <h2>Articles aimés</h2>
    <ul>
        @forelse($user->likes as $article)
            <li>{{ $article->titre }}</li>
        @empty
            <li>Aucun article aimé pour le moment.</li>
        @endforelse
    </ul>
</section>

<section>
    <h2>Rythmes et ambiances préférés</h2>
    <div class="tags">
        @foreach($rythmes as $rythme)
            <span class="badge">{{ $rythme }}</span>
        @endforeach
    </div>
</section>