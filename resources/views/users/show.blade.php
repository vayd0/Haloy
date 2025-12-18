@extends("layout.app")

@section('contenu')
    <div class="profile-container">
        <h1>Profil de {{ $user->name }}</h1>

        <div class="stats">
            <p><strong>{{ $user->suiveurs->count() }}</strong> followers</p>
            <p><strong>{{ $user->suivis->count() }}</strong> abonnements</p>
        </div>

        {{-- Bouton Suivre / Ne plus suivre (Visible seulement pour les autres) --}}
        @auth
            @if(auth()->id() !== $user->id)
                <form action="{{ route('users.follow', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        {{ auth()->user()->suivis->contains($user->id) ? 'Ne plus suivre' : 'Suivre' }}
                    </button>
                </form>
            @endif
        @endauth

        {{-- ZONE NOTIFICATIONS (Visible seulement pour le propriétaire du profil) --}}
        @if(auth()->check() && auth()->id() === $user->id)
            <section class="notifications-section" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 5px solid #3b82f6;">
                <h2 style="font-size: 1.2em; margin-bottom: 10px;">🔔 Vos notifications</h2>

                @if($user->unreadNotifications->isEmpty())
                    <p style="color: #666; font-style: italic;">Aucune nouvelle notification.</p>
                @else
                    <ul style="list-style: none; padding: 0;">
                        @foreach($user->unreadNotifications as $notification)
                            <li style="padding: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong>{{ $notification->data['auteur_name'] }}</strong> a publié un nouvel article :
                                    <a href="{{ route('article.show', $notification->data['article_id']) }}" style="color: #2563eb; font-weight: bold;">
                                        {{ $notification->data['article_titre'] }}
                                    </a>
                                    <div style="font-size: 0.8em; color: #888;">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                {{-- Optionnel : Bouton pour marquer comme lu --}}
                                {{-- <a href="{{ route('notifications.read', $notification->id) }}" style="font-size: 0.8em;">Marquer lu</a> --}}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        @endif

        {{-- Articles aimés --}}
        <section>
            <h2>Articles aimés</h2>
            <ul>
                @forelse($user->likes as $article)
                    <li>
                        <a href="{{ route('article.show', $article->id) }}">{{ $article->titre }}</a>
                    </li>
                @empty
                    <li>Aucun article aimé pour le moment.</li>
                @endforelse
            </ul>
        </section>

        {{-- Rythmes préférés --}}
        <section>
            <h2>Rythmes et ambiances préférés</h2>
            <div class="tags">
                @foreach($rythmes as $rythme)
                    <span class="badge" style="background: #e2e8f0; padding: 5px 10px; border-radius: 15px; margin-right: 5px;">
                        {{ $rythme->texte ?? $rythme }}
                    </span>
                @endforeach
            </div>
        </section>
    </div>
@endsection