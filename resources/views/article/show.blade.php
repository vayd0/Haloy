@extends("layout.app")

@section('contenu')
<div class="article-container">
    <!-- Header  -->
    <div class="article-header">
        <h1>{{ $article->titre }}</h1>
        <div class="article-meta">
            <span class="author-info">
                @if ($article->editeur->avatar)
                    <img src="{{ asset('storage/' . $article->editeur->avatar) }}" alt="{{ $article->editeur->name }}" class="avatar-small">
                @else
                    <div class="avatar-small" style="background: #ccc; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">{{ substr($article->editeur->name, 0, 1) }}</div>
                @endif
                <span class="author-name">{{ $article->editeur->name }}</span>
            </span>
            <span class="article-date">
                @if ($article->updated_at != $article->created_at)
                    Modifié le {{ $article->updated_at->format('d/m/Y à H:i') }}
                @else
                    Publié le {{ $article->created_at->format('d/m/Y à H:i') }}
                @endif
            </span>
            <span class="article-views">
                 {{ $article->nb_vues }} vue{{ $article->nb_vues > 1 ? 's' : '' }}
            </span>
        </div>
    </div>

    <!-- Image -->
    @if ($article->image)
        <div class="article-image">
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}">
        </div>
    @endif

    <!-- Sommaire -->
    <div class="article-summary">
        <h2>Résumé</h2>
        <p>{{ $article->resume }}</p>
    </div>

    <!-- Section caractéristiques -->
    <div class="article-characteristics">
        <h2>Caractéristiques</h2>
        <div class="characteristics-grid">
            <!-- Rythme -->
            <div class="characteristic-card">
                <h3>{{ $article->rythme->texte ?? 'Non défini' }}</h3>
                @if ($article->rythme && $article->rythme->image)
                    <img src="{{ asset('storage/' . $article->rythme->image) }}" alt="Rythme: {{ $article->rythme->texte }}">
                @endif
                <p class="characteristic-label">Rythme</p>
            </div>

            <!-- Accessibilité -->
            <div class="characteristic-card">
                <h3>{{ $article->accessibilite->texte ?? 'Non défini' }}</h3>
                @if ($article->accessibilite && $article->accessibilite->image)
                    <img src="{{ asset('storage/' . $article->accessibilite->image) }}" alt="Accessibilité: {{ $article->accessibilite->texte }}">
                @endif
                <p class="characteristic-label">Accessibilité</p>
            </div>

            <!-- Conclusion -->
            <div class="characteristic-card">
                <h3>{{ $article->conclusion->texte ?? 'Non défini' }}</h3>
                @if ($article->conclusion && $article->conclusion->image)
                    <img src="{{ asset('storage/' . $article->conclusion->image) }}" alt="Conclusion: {{ $article->conclusion->texte }}">
                @endif
                <p class="characteristic-label">Conclusion</p>
            </div>
        </div>
    </div>

    <!-- Section audio -->
    <div class="article-media">
        <h2>Média Audio</h2>
        <div class="audio-player-container">
            <audio controls class="audio-player">
                <source src="{{ asset('storage/' . $article->media) }}" type="audio/mpeg">
                Votre navigateur ne supporte pas l'élément audio.
            </audio>
        </div>
    </div>

    <!-- Article -->
    <div class="article-content">
        <h2>Article complet</h2>
        <div class="article-text">
            {!! nl2br(e($article->texte)) !!}
        </div>
    </div>

    <!-- Section j'aime -->
    <div class="article-interactions">
        <h2>Avis des lecteurs</h2>
        <div class="likes-section">
            <div class="like-stats">
                <span class="like-count">
                    <strong>👍 {{ $likesCount }}</strong> J'aime
                </span>
                <span class="dislike-count">
                    <strong>👎 {{ $dislikesCount }}</strong> Je n'aime pas
                </span>
            </div>

            @auth
                <div class="like-actions">
                    <form action="{{ route('article.like', $article->id) }}" method="POST" class="like-form">
                        @csrf
                        <input type="hidden" name="nature" value="1">
                        <button type="submit" class="btn btn-like {{ $userLikeStatus === true ? 'active' : '' }}">
                            👍 J'aime
                        </button>
                    </form>
                    <form action="{{ route('article.dislike', $article->id) }}" method="POST" class="like-form">
                        @csrf
                        <input type="hidden" name="nature" value="0">
                        <button type="submit" class="btn btn-dislike {{ $userLikeStatus === false ? 'active' : '' }}">
                            👎 Je n'aime pas
                        </button>
                    </form>
                    @if ($userLikeStatus !== null)
                        <form action="{{ route('article.unlike', $article->id) }}" method="POST" class="like-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-unlike">
                                ✕ Retirer
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <div class="login-prompt">
                    <p><a href="{{ route('login') }}">Connectez-vous</a> pour voter sur cet article</p>
                </div>
            @endauth
        </div>
    </div>

    <!-- Section commentaires -->
    <div class="article-comments">
        <h2>Commentaires ({{ $article->avis->count() }})</h2>

        @auth
            <div class="comment-form">
                <h3>Ajouter un commentaire</h3>
                <form action="{{ route('article.comment', $article->id) }}" method="POST">
                    @csrf
                    <textarea name="contenu" rows="4" placeholder="Votre commentaire..." required></textarea>
                    <button type="submit" class="btn btn-primary">Publier</button>
                </form>
            </div>
        @else
            <div class="login-prompt">
                <p><a href="{{ route('login') }}">Connectez-vous</a> pour ajouter un commentaire</p>
            </div>
        @endauth

        <!-- Liste des commentaires -->
        @if ($avis->count() > 0)
            <div class="comments-list">
                @foreach ($avis as $comment)
                    <div class="comment">
                        <div class="comment-header">
                            <div class="comment-author">
                                @if ($comment->user->avatar)
                                    <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="{{ $comment->user->name }}" class="avatar-small">
                                @endif
                                <strong>{{ $comment->user->name }}</strong>
                                <span class="comment-date">{{ $comment->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </div>
                        <p class="comment-content">{{ $comment->contenu }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $avis->links() }}
            </div>
        @else
            <p class="no-comments">Aucun commentaire pour le moment. Soyez le premier à commenter!</p>
        @endif
    </div>

    <!-- Section article similaires -->
    @if ($similarArticles->count() > 0)
        <div class="similar-articles">
            <h2>Articles similaires</h2>
            <div class="articles-grid">
                @foreach ($similarArticles as $similarArticle)
                    <div class="article-card">
                        @if ($similarArticle->image)
                            <img src="{{ asset('storage/' . $similarArticle->image) }}" alt="{{ $similarArticle->titre }}">
                        @endif
                        <div class="article-card-content">
                            <h3>
                                <a href="{{ route('article.show', $similarArticle->id) }}">
                                    {{ Str::limit($similarArticle->titre, 50) }}
                                </a>
                            </h3>
                            <p class="article-card-author">par {{ $similarArticle->editeur->name }}</p>
                            <p class="article-card-summary">{{ Str::limit($similarArticle->resume, 100) }}</p>
                            <div class="article-card-meta">
                                <span>{{ $similarArticle->nb_vues }} vues</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

