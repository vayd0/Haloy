@extends("layout.app")

@section('contenu')
    <style>
        #scene-container {
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            background: transparent;
            z-index: 10;
            position: absolute;
            inset: 0;
        }
    </style>
    <section class="relative w-full">
        <h1 class="scd-font" id="hero-text">Haloy</h1>
        <div class="flex justify-start items-center absolute top-1/3 right-[-50%] glass-morph w-[300px] h-[50px] p-4">
            <div class="text-sm font-light">
                Votre blog de métal symphonique.
            </div>
        </div>
        <div class="flex justify-start items-center absolute top-2/3 left-[-40%] glass-morph w-[200px] h-[50px] p-4">
            <div class="text-sm font-light">
                <a href="#insta" target="_blank" class="mx-2 text-xl">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#twitter" target="_blank" class="mx-2 text-xl">
                    <i class="fab fa-x-twitter"></i>
                </a>
                <a href="#youtube" target="_blank" class="mx-2 text-xl">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
        <!--<div id="scene-container"></div>-->
    </section>
    @vite('resources/js/vinyl.js')
    @vite('resources/css/home.css')
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
                        <span class="vues">{{ $article->nb_vues }} vues</span>
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