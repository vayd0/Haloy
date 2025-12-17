@extends("layout.app")

@section('contenu')
    <h1>Bienvenue sur notre site d'articles musicaux</h1>

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

                    @if($article->rythme)
                        <div class="rythme">
                            Rythme : {{ $article->rythme->texte }}
                        </div>
                    @endif
                </a>
            </article>
        @empty
            <p>Aucun article disponible pour le moment.</p>
        @endforelse
    </div>

    <div class="pagination">
        {{ $articles->links() }}
    </div>
@endsection