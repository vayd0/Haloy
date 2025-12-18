@extends("layout.app")

@section('contenu')
    <div class="caracteristique-header pt-[8rem]">
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
            <!-- #region -->             <span class="auteur">Par {{ $article->editeur->name }}</span>
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

    <div class="relative mt-8 z-[99]">
        <div class="mt-8 flex justify-center items-center gap-4">
            @if ($articles->onFirstPage())
                <span class="opacity-50 cursor-not-allowed">
                    <img src="{{ asset('images/flèche.svg') }}" alt="Précédent" class="w-16 h-16 rotate-[-180deg] " />
                </span>
            @else
                <a href="{{ $articles->previousPageUrl() }}">
                    <img src="{{ asset('images/flèche.svg') }}" alt="Précédent"
                        class="w-16 h-16 rotate-[-180deg] hover:scale-110 transition" />
                </a>
            @endif

            <span class="mx-2 text-lg font-semibold">
                {{ $articles->currentPage() }} / {{ $articles->lastPage() }}
            </span>

            @if ($articles->hasMorePages())
                <a href="{{ $articles->nextPageUrl() }}">
                    <img src="{{ asset('images/flèche.svg') }}" alt="Suivant" class="w-16 h-16 hover:scale-110 transition" />
                </a>
            @else
                <span class="opacity-50 cursor-not-allowed">
                    <img src="{{ asset('images/flèche.svg') }}" alt="Suivant" class="w-16 h-16" />
                </span>
            @endif
        </div>
    </div>

    <div class="back-link">
        <a href="{{ route('accueil') }}">← Retour à l'accueil</a>
    </div>
@endsection