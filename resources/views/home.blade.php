@php use App\Models\Article; @endphp
@extends("layout.app")

@section('contenu')
    <div class="container mx-auto px-2 sm:px-4 py-6 sm:py-8">
        <h1 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6">Articles</h1>

        @if($articles->isEmpty())
            <p class="text-gray-600">Aucun article disponible.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($articles as $article)
                    <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow bg-white flex flex-col h-full">
                        @if($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}" class="w-full h-40 sm:h-48 object-cover">
                        @else
                            <div class="w-full h-40 sm:h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-sm">Pas d'image</span>
                            </div>
                        @endif

                        <div class="p-3 sm:p-4 flex flex-col flex-1">
                            <h3 class="font-bold text-base sm:text-lg mb-1 sm:mb-2">{{ $article->titre }}</h3>
                            <p class="text-gray-600 text-xs sm:text-sm mb-2 sm:mb-4 line-clamp-3">{{ $article->resume }}</p>
                            <div class="flex justify-between items-center mt-auto">
                                <span class="text-xs text-gray-500">Créé le {{ $article->created_at->format('d/m/Y') }}</span>
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">Lire la suite</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
