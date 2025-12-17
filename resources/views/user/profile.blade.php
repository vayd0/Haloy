{{-- Page de profil utilisateur : liste des articles en cours de rédaction --}}
@extends('layout.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Ma page personnelle</h1>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Mes articles en cours de rédaction</h2>

            @if($articles->isEmpty())
                <p class="text-gray-600">Vous n'avez aucun article en cours de rédaction.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($articles as $article)
                        <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            @if($article->image)
                                <img src="{{ Vite::asset('resources/images/' . $article->image) }}" alt="{{ $article->titre }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">Pas d'image</span>
                                </div>
                            @endif

                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-2">{{ $article->titre }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $article->resume }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Créé le {{ $article->created_at->format('d/m/Y') }}</span>
                                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Modifier</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
