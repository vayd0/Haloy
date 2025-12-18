@php use App\Models\Article; @endphp
@extends("layout.app")

@section('contenu')
    <div class="flex justify-center items-center px-[5rem] pt-[10rem]">
        <div>
            <div class="relative flex justify-between items-center">
                <h1 class="text-3xl font-bold mb-6 title flex justify-between w-full">Tous les articles </h1>
                <button
                    class="glass-morph h-10 w-10 p-2 hover:scale-105 transition-all duration-300 hover:rotate-[10deg] flex justify-center items-center"
                    id="dropdown-toggle2"><i class="fa-solid fa-filter text-[20px]"></i>
                </button>
                <div class="absolute flex flex-col glass-morph top-[3rem] right-0 mt-2 max-h-48 overflow-y-auto w-60 rounded shadow-lg p-3 transition-all z-50 opacity-100 text-right hidden"
                    id="dropdown-menu2">
                    @foreach ($accessibilites as $access)
                        <a href="{{ route('accessibilite.articles', $access->id) }}">{{$access->texte}}</a>
                    <hr>
                        @endforeach

                    @foreach ($rythmes as $rythme)
                        <a href="{{ route('rythme.articles', $rythme->id) }}">{{$rythme->texte}}</a>
                        <hr>
                    @endforeach

                    @foreach ($conclusions as $conclusion)
                        <a href="{{ route('conclusion.articles', $conclusion->id) }}">{{$conclusion->texte}}</a>
                        <hr>
                    @endforeach
                </div>
            </div>
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
                                <img src="{{ asset('images/flèche.svg') }}" alt="Précédent"
                                    class="w-16 h-16 rotate-[-180deg] hover:scale-110 transition" />
                            </a>
                        @endif

                        <span class="mx-2 text-lg font-semibold">
                            {{ $articles->currentPage() }} / {{ $articles->lastPage() }}
                        </span>

                        @if ($articles->hasMorePages())
                            <a href="{{ $articles->nextPageUrl() }}">
                                <img src="{{ asset('images/flèche.svg') }}" alt="Suivant"
                                    class="w-16 h-16 hover:scale-110 transition" />
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