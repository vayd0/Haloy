@php use App\Models\Article; @endphp
@extends("layout.app")

@section('contenu')
    <div class="flex justify-center items-center px-[5rem] pt-[10rem]">
        <div>
            <h1 class="text-3xl font-bold mb-6 title">Tous les articles</h1>

        @if($articles->isEmpty())
            <p class="text-gray-600">Aucun article disponible.</p>
        @else
            <div class="articles-grid flex justify-around flex-wrap gap-[3.5rem] w-full mt-16">
                @foreach($articles as $article)
                    <x-cards.article-card :article="$article" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        @endif
        </div>
    </div>
@endsection