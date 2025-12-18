{{-- Page de profil utilisateur : liste des articles en cours de rédaction --}}
@extends('layout.app')

@section('contenu')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Ma page personnelle</h1>

        {{-- Section Notifications --}}
        @if(isset($notifications) && $notifications->isNotEmpty())
            <div class="bg-blue-50 shadow-md rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">Nouveaux articles de vos abonnements</h2>
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="bg-white border border-blue-200 rounded-lg p-4 flex items-start gap-4">
                            @if($notification->data['article_image'])
                                <img src="{{ asset('storage/' . $notification->data['article_image']) }}"
                                     alt="{{ $notification->data['article_titre'] }}"
                                     class="w-20 h-20 object-cover rounded">
                            @endif
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">
                                    <strong>{{ $notification->data['auteur_name'] }}</strong> a publié un nouvel article
                                </p>
                                <a href="{{ route('article.show', $notification->data['article_id']) }}"
                                   class="text-lg font-semibold text-blue-600 hover:text-blue-800">
                                    {{ $notification->data['article_titre'] }}
                                </a>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->data['article_resume'] }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Section Utilisateurs suggérés --}}
        @if(isset($suggestedUsers) && $suggestedUsers->isNotEmpty())
            <div class="bg-green-50 shadow-md rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">Personnes aux profils similaires</h2>
                <p class="text-sm text-gray-600 mb-4">Ces utilisateurs aiment les mêmes types d'articles que vous</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($suggestedUsers as $suggestedUser)
                        <div class="bg-white border border-green-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-3">
                                @if($suggestedUser->avatar)
                                    <img src="{{ asset('storage/' . $suggestedUser->avatar) }}"
                                         alt="{{ $suggestedUser->name }}"
                                         class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold">
                                        {{ substr($suggestedUser->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <a href="{{ route('users.show', $suggestedUser->id) }}"
                                       class="font-semibold text-gray-800 hover:text-green-600">
                                        {{ $suggestedUser->name }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ $suggestedUser->likes_count }} articles similaires</p>
                                </div>
                            </div>
                            <form action="{{ route('users.follow', $suggestedUser->id) }}" method="POST">
                                @csrf
                                {{-- Utilisation du composant follow-button --}}
                                <x-follow.button :user-id="$suggestedUser->id" :is-following="$suggestedUser->is_followed" />
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Mes articles en cours de rédaction</h2>

            @if($articles->isEmpty())
                <p class="text-gray-600">Vous n'avez aucun article en cours de rédaction.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($articles as $article)
                        <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}" class="w-full h-48 object-cover">
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
