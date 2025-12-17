@extends("layout.app")

@section('contenu')
<div class="article-container">
    <!-- Header Section -->
    <div class="article-header">
        <h1>{{ $article->titre }}</h1>
        <div class="article-meta">
            <span class="author-info">
                <img src="{{ asset('storage/' . $article->editeur->avatar) }}" alt="{{ $article->editeur->name }}" class="avatar-small">
                <span class="author-name">{{ $article->editeur->name }}</span>
            </span>
            <span class="article-date">
                @if ($article->updated_at != $article->created_at)
                    Modifié le {{ $article->updated_at->format('d/m/Y à H:i') }}
                @else
                    Publié le {{ $article->created_at->format('d/m/Y à H:i') }}
                @endif
            </span>
        </div>
    </div>

    <!-- Featured Image -->
    @if ($article->image)
        <div class="article-image">
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}">
        </div>
    @endif

    <!-- Summary Section -->
    <div class="article-summary">
        <h2>Résumé</h2>
        <p>{{ $article->resume }}</p>
    </div>

    <!-- Characteristics Section -->
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

    <!-- Audio Player Section -->
    <div class="article-media">
        <h2>Média Audio</h2>
        <div class="audio-player-container">
            <audio controls class="audio-player">
                <source src="{{ asset('storage/' . $article->media) }}" type="audio/mpeg">
                Votre navigateur ne supporte pas l'élément audio.
            </audio>
        </div>
    </div>

    <!-- Article Content -->
    <div class="article-content">
        <h2>Article complet</h2>
        <div class="article-text">
            {!! nl2br(e($article->texte)) !!}
        </div>
    </div>

    <!-- Likes Section -->
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
                </div>
            @else
                <div class="login-prompt">
                    <p><a href="{{ route('login') }}">Connectez-vous</a> pour voter sur cet article</p>
                </div>
            @endauth
        </div>
    </div>

    <!-- Comments Section -->
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

        <!-- Comments List -->
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

    <!-- Similar Articles Section -->
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

<style>
    .article-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .article-header {
        border-bottom: 2px solid #ddd;
        padding-bottom: 2rem;
        margin-bottom: 2rem;
    }

    .article-header h1 {
        font-size: 2.5rem;
        margin: 0 0 1rem 0;
        color: #333;
    }

    .article-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.95rem;
        color: #666;
    }

    .author-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .avatar-small {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    .article-image {
        margin: 2rem 0;
    }

    .article-image img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .article-summary,
    .article-content {
        margin: 2rem 0;
        line-height: 1.8;
    }

    .article-summary h2,
    .article-content h2,
    .article-characteristics h2,
    .article-media h2,
    .article-interactions h2,
    .article-comments h2,
    .similar-articles h2 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 1rem;
    }

    .article-text {
        color: #444;
        font-size: 1.1rem;
        line-height: 1.8;
    }

    /* Characteristics Section */
    .article-characteristics {
        margin: 2rem 0;
        padding: 1.5rem;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .characteristics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .characteristic-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .characteristic-card h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.2rem;
        color: #333;
    }

    .characteristic-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 4px;
        margin: 0.5rem 0;
    }

    .characteristic-label {
        font-size: 0.9rem;
        color: #999;
        margin: 0;
    }

    /* Audio Player */
    .article-media {
        margin: 2rem 0;
        padding: 1.5rem;
        background: #f0f0f0;
        border-radius: 8px;
    }

    .audio-player-container {
        width: 100%;
    }

    .audio-player {
        width: 100%;
        min-height: 40px;
    }

    /* Likes Section */
    .article-interactions {
        margin: 2rem 0;
        padding: 1.5rem;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .likes-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .like-stats {
        display: flex;
        gap: 2rem;
        font-size: 1.1rem;
    }

    .like-count,
    .dislike-count {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .like-actions {
        display: flex;
        gap: 1rem;
    }

    .like-form {
        flex: 1;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-like,
    .btn-dislike {
        flex: 1;
        background: #f0f0f0;
        color: #333;
        border: 2px solid #ddd;
    }

    .btn-unlike {
        flex: 1;
        background: #f0f0f0;
        color: #999;
        border: 2px solid #ddd;
    }

    .btn-unlike:hover {
        background: #ffcdd2;
        border-color: #d32f2f;
        color: #d32f2f;
    }

    .btn-like:hover {
        background: #e8f5e9;
        border-color: #4caf50;
    }

    .btn-dislike:hover {
        background: #ffebee;
        border-color: #f44336;
    }

    .btn-like.active {
        background: #4caf50;
        color: white;
        border-color: #4caf50;
    }

    .btn-dislike.active {
        background: #f44336;
        color: white;
        border-color: #f44336;
    }

    .btn-primary {
        background: #007bff;
        color: white;
        margin-top: 0.5rem;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .login-prompt {
        padding: 1rem;
        background: #e3f2fd;
        border-radius: 4px;
        color: #1976d2;
    }

    .login-prompt a {
        color: #1976d2;
        text-decoration: underline;
    }

    /* Comments Section */
    .article-comments {
        margin: 2rem 0;
    }

    .comment-form {
        background: #f9f9f9;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .comment-form h3 {
        margin-top: 0;
    }

    .comment-form textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: inherit;
        resize: vertical;
    }

    .comments-list {
        margin: 1.5rem 0;
    }

    .comment {
        padding: 1.5rem;
        background: #f9f9f9;
        border-left: 3px solid #007bff;
        border-radius: 4px;
        margin-bottom: 1rem;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .comment-author {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .comment-date {
        font-size: 0.9rem;
        color: #999;
        margin-left: 0.5rem;
    }

    .comment-content {
        margin: 0;
        color: #444;
        line-height: 1.6;
    }

    .no-comments {
        text-align: center;
        color: #999;
        padding: 2rem;
        font-style: italic;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin: 2rem 0;
    }

    /* Similar Articles */
    .similar-articles {
        margin: 3rem 0;
        padding-top: 2rem;
        border-top: 2px solid #ddd;
    }

    .articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .article-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .article-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .article-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .article-card-content {
        padding: 1rem;
    }

    .article-card-content h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
    }

    .article-card-content a {
        color: #007bff;
        text-decoration: none;
    }

    .article-card-content a:hover {
        text-decoration: underline;
    }

    .article-card-author {
        font-size: 0.9rem;
        color: #666;
        margin: 0.25rem 0;
    }

    .article-card-summary {
        font-size: 0.95rem;
        color: #444;
        margin: 0.5rem 0;
        line-height: 1.5;
    }

    .article-card-meta {
        font-size: 0.85rem;
        color: #999;
        margin-top: 0.75rem;
    }

    @media (max-width: 768px) {
        .article-header h1 {
            font-size: 1.8rem;
        }

        .article-meta {
            flex-direction: column;
            align-items: flex-start;
        }

        .characteristics-grid {
            grid-template-columns: 1fr;
        }

        .like-stats {
            flex-direction: column;
            gap: 0.5rem;
        }

        .like-actions {
            flex-direction: column;
        }

        .articles-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

