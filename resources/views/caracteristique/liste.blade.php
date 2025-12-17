@extends("layout.app")

@section('contenu')
    <div class="caracteristique-header">
        <h1>Articles : {{ $type }}</h1>
        <p class="caracteristique-description">{{ $caracteristique->texte }}</p>
        <p class="articles-count">{{ $articles->total() }} article(s) trouvé(s)</p>
    </div>

    <div class="articles-grid">
        @forelse($articles as $article)
            <article class="article-card">
                <a href="{{ route('article.show', $article->id) }}">
                    @if($article->image)
                        <img src="{{ $article->image }}" alt="{{ $article->titre }}">
                    @endif

                    <h2>{{ $article->titre }}</h2>

                    <p class="resume">{{ $article->resume }}</p>

                    <div class="article-meta">
                        <span class="auteur">Par {{ $article->editeur->name }}</span>
                    </div>

                    <div class="article-characteristics">
                        @if($article->rythme)
                            <div class="characteristic-badge">
                                Rythme : {{ $article->rythme->texte }}
                            </div>
                        @endif
                        @if($article->accessibilite)
                            <div class="characteristic-badge">
                                Accessibilité : {{ $article->accessibilite->texte }}
                            </div>
                        @endif
                        @if($article->conclusion)
                            <div class="characteristic-badge">
                                Conclusion : {{ $article->conclusion->texte }}
                            </div>
                        @endif
                    </div>
                </a>
            </article>
        @empty
            <p>Aucun article disponible pour cette caractéristique.</p>
        @endforelse
    </div>

    <div class="pagination">
        {{ $articles->links() }}
    </div>

    <div class="back-link">
        <a href="{{ route('accueil') }}">← Retour à l'accueil</a>
    </div>
@endsection