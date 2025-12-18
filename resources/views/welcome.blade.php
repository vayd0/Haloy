@extends("layout.app")

@section('contenu')
    <section class="relative min-h-screen flex flex-col items-center justify-start pt-10 w-full">
        <div class="h-[80vh] w-full">
            <div class="relative w-full h-[80vh] flex items-center justify-center">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="relative w-fit mx-auto">
                        <h1 class="scd-font pointer-events-none text-transparent text-[8rem] sm:text-[13rem] md:text-[15rem] lg:text-[20rem] text-center z-[20] absolute inset-0 select-none"
                            id="hero-stroke-text">
                            Haloy
                        </h1>
                        <h1 class="scd-font pointer-events-none text-redc/70 text-[8rem] sm:text-[13rem] md:text-[15rem] lg:text-[20rem] text-center z-[10] relative select-none"
                            id="hero-text">
                            Haloy
                        </h1>
                    </div>
                    <div class="absolute z-[15] inset-0" id="scene-container"></div>
                </div>
                <div
                    class="flex justify-start items-center absolute top-[15rem] right-[5vw] md:right-[22vw] glass-morph w-auto h-[50px] p-4 z-[99]">
                    <div class="text-sm font-light">
                        Votre blog de métal symphonique.
                    </div>
                </div>
                <div
                    class="flex justify-end items-center absolute top-2/3 left-[5vw] md:left-[30vw] glass-morph w-auto h-[50px] p-4 z-[99]">
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
        </div>
        <article>
            <h1 class="title text-center mb-4 text-[2rem]">Articles récents</h1>
            <div class="articles-grid flex justify-center flex-wrap gap-[3.5rem] w-full max-w-6xl mt-16">

                @foreach($articles as $article)
                    <x-cards.article-card :article="$article" />
                @endforeach
                @if($articles->isEmpty())
                    <p>Aucun article disponible pour le moment.</p>
                @endif
            </div>
        </article>

        <div class="mt-[4rem] text-center">
            <a href="{{ route('articles.all') }}"
                class="relative inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 z-[99]">
                Voir tous les articles
            </a>
        </div>
    </section>

    @vite('resources/js/vinyl.js')
    @vite('resources/css/home.css')
@endsection