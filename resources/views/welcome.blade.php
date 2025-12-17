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
    <section class="relative min-h-screen flex flex-col items-center justify-start pt-32 w-full">
        <div class="relative w-full flex flex-col items-center" style="overflow: visible;">
            <h1 class="scd-font ;" id="hero-text">Haloy</h1>
            <div class="flex justify-start items-center absolute top-[6rem] right-[10vw] glass-morph w-[300px] h-[50px] p-4">
                <div class="text-sm font-light">
                    Votre blog de métal symphonique.
                </div>
            </div>
            <div class="flex justify-start items-center absolute top-2/3 left-[10vw] glass-morph w-[200px] h-[50px] p-4">
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
        </div>

        <div class="articles-grid flex justify-center flex-wrap gap-[3.5rem] w-full max-w-6xl mt-16">
            @forelse($articles as $article)
                <x-cards.article-card :article="$article" />
            @empty
                <p>Aucun article disponible pour le moment.</p>
            @endforelse
        </div>

        <div class="pagination mt-8">
            {{ $articles->links() }}
        </div>
    </section>

    @vite('resources/js/vinyl.js')
    @vite('resources/css/home.css')
@endsection