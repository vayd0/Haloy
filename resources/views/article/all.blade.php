@php use App\Models\Article; @endphp
@extends("layout.app")

@section('contenu')
    <div class="flex justify-center items-center px-[5rem] pt-[10rem]">
        <div>
            <h1 class="text-3xl font-bold mb-6 title">Tous les articles</h1>

            @if($articles->isEmpty())
                <p class="text-gray-600">Aucun article disponible.</p>
            @else
                <div class="articles-grid flex justify-around flex-wrap gap-[2.5rem] w-full mt-16">
                    @foreach($articles as $article)
                        <x-cards.article-card :article="$article" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="relative mt-8 z-[99]">
                    <div class="mt-8 flex justify-center items-center gap-4">
                        @if ($articles->onFirstPage())
                            <span class="opacity-50 cursor-not-allowed">
                                <img src="{{ asset('images/flèche.svg') }}" alt="Précédent" class="w-16 h-16 rotate-[-180deg] " />
                            </span>
                        @else
                            <a href="{{ $articles->previousPageUrl() }}">
                                <img src="{{ asset('images/flèche.svg') }}" alt="Précédent" class="w-16 h-16 rotate-[-180deg] hover:scale-110 transition" />
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
            @endif
        </div>
    </div>
@endsection