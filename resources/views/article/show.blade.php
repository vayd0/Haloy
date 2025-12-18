@extends("layout.app")

@section('contenu')
    <div class="article-container pt-[10rem] w-2/3">
        <div class="article-header grid grid-cols-2 grid-rows-4 gap-4 items-top">
            <div class="article-media row-span-1 col-span-1 flex items-top w-full h-full">
                <div class="audio-player-container">
                    <div id="custom-audio-player" class="custom-audio-player bg-white/50"
                        style="background: linear-gradient(rgba(255,255,255,1), rgba(255,255,255,1)), url('{{ asset('storage/' . $article->image) }}'); background-size: cover;">
                        <button id="play-pause" class="play"><img class="w-[20rem]" src="{{ asset('images/Play.png') }}"
                                alt=""></button>
                        <div>
                            <span class="text-redc" id="current-time">0:00</span>
                            <input type="range" id="seek-bar" value="0" min="0" step="1">
                        </div>
                        <audio id="audio" src="{{  $article->media }}"></audio>
                    </div>
                </div>
            </div>
            <div class="flex-col justify-center items-top">
                <h1 class="title text-[2.5rem] font-bold">{{ $article->titre }}</h1>
                <div class="article-meta flex gap-1 justify-between w-full">
                    <span class="author-info text-white">
                        <span class="author-name">{{ $article->editeur->name }}</span>
                    </span>
                    <span class="article-date text-white text-sm">
                        @if ($article->updated_at != $article->created_at)
                            Modifié le {{ $article->updated_at->format('d/m/Y à H:i') }}
                        @else
                            Publié le {{ $article->created_at->format('d/m/Y à H:i') }}
                        @endif
                    </span>
                </div>
                <span class="article-views text-left self-right text-white flex justify-between items-center mt-5">
                    <div class="article-interactions glass-morph p-2 px-4 w-auto">
                        <div class="likes-section flex justify-between items-center">
                            @auth
                                <x-like.button :article-id="$article->id" :user-like-status="$userLikeStatus"
                                    :likesCount="$likesCount" :dislikesCount="$dislikesCount" />
                            @else
                                <div class="login-prompt">
                                    <p><a href="{{ route('login') }}">Connectez-vous</a> pour voter sur cet article</p>
                                </div>
                            @endauth
                        </div>
                    </div>
                    <span class="w-[150px] text-right text-[12px] p-2 glass-morph flex justify-center gap-2 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path fill="#fff" fill-rule="evenodd"
                                d="M4.998 7.78C6.729 6.345 9.198 5 12 5c2.802 0 5.27 1.345 7.002 2.78a12.7 12.7 0 0 1 2.096 2.183c.253.344.465.682.618.997.14.286.284.658.284 1.04s-.145.754-.284 1.04c-.176.35-.383.684-.618.997a12.7 12.7 0 0 1-2.096 2.183C17.271 17.655 14.802 19 12 19c-2.802 0-5.27-1.345-7.002-2.78a12.7 12.7 0 0 1-2.096-2.183 6.6 6.6 0 0 1-.618-.997C2.144 12.754 2 12.382 2 12s.145-.754.284-1.04c.153-.315.365-.653.618-.997A12.7 12.7 0 0 1 4.998 7.78ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $article->nb_vues }} vue{{ $article->nb_vues > 1 ? 's' : '' }}
                    </span>
                </span>
            </div>
            @if ($article->image)
                <div class="article-image row-span-2 col-span-1 flex items-center">
                    <img class="rounded-xl w-full h-full object-cover shadow" src="{{ asset('storage/' . $article->image) }}"
                        alt="{{ $article->titre }}" onerror="this.onerror=null;this.src='{{ $article->image }}';">
                </div>
            @endif
            <div class="article-summary">
                <h2 class="text-lg font-semibold">Résumé</h2>
                <p>{{ $article->resume }}</p>
            </div>
            <div class="article-characteristics">
                <h2 class="text-lg font-semibold">Caractéristiques</h2>
                <div class="characteristics-grid">
                    @if($article->rythme)
                        <div class="characteristic-card">
                            <a href="{{ route('rythme.articles', $article->rythme->id) }}">
                                <h3>{{ $article->rythme->texte }}</h3>
                                @if ($article->rythme->image)
                                    <img src="{{ asset('storage/' . $article->rythme->image) }}"
                                        alt="Rythme: {{ $article->rythme->texte }}">
                                @endif
                                <p class="characteristic-label">Rythme</p>
                            </a>
                        </div>
                    @endif

                    @if($article->accessibilite)
                        <div class="characteristic-card">
                            <a href="{{ route('accessibilite.articles', $article->accessibilite->id) }}">
                                <h3>{{ $article->accessibilite->texte }}</h3>
                                @if ($article->accessibilite->image)
                                    <img src="{{ asset('storage/' . $article->accessibilite->image) }}"
                                        alt="Accessibilité: {{ $article->accessibilite->texte }}">
                                @endif
                                <p class="characteristic-label">Accessibilité</p>
                            </a>
                        </div>
                    @endif

                    @if($article->conclusion)
                        <div class="characteristic-card">
                            <a href="{{ route('conclusion.articles', $article->conclusion->id) }}">
                                <p class="characteristic-label">Conclusion</p>
                                <h3>{{ $article->conclusion->texte }}</h3>
                                @if ($article->conclusion->image)
                                    <img src="{{ asset('storage/' . $article->conclusion->image) }}"
                                        alt="Conclusion: {{ $article->conclusion->texte }}">
                                @endif
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="article-content">
            <h2>Article complet</h2>
            <div class="article-text prose max-w-none">
                @markdown($article->texte)
            </div>
        </div>

        <div class="article-comments mt-4">

            @if(session('success'))
                <div class="alert alert-success" style="color: green; margin-bottom: 15px;">
                    {{ session('success') }}
                </div>
            @endif

            @auth
                <div class="comment-form">
                    <form class="flex justify-between items-center" action="{{ route('article.comment', $article->id) }}"
                        method="POST">
                        @csrf
                        <textarea class="glass-morph w-2/3 h-10 flex p-2 px-4" name="contenu" rows="4"
                            placeholder="Votre commentaire..." required></textarea>
                        @error('contenu')
                            <div class="text-red-500 mt-2 text-sm" style="color: red;">
                                {{ $message }}
                            </div>
                        @enderror
                        <button class="h-10 w-10 glass-morph flex items-center justify-center" type="submit">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            @else
                <div class="login-prompt">
                    <p><a href="{{ route('login') }}">Connectez-vous</a> pour ajouter un commentaire</p>
                </div>
            @endauth

            @if ($avis->count() > 0)
                <div class="comments-list">
                    @foreach ($avis as $comment)
                        <div class="comment glass-morph p-6 mt-4">
                            <div class="comment-header">
                                <div class="comment-author flex justify-between items-center">
                                    <strong class="mb-4">{{ $comment->user->name }}</strong>
                                    <span class="comment-date">{{ $comment->created_at->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                            <p class="comment-content mt-4">{{ $comment->contenu }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="pagination-wrapper">
                    {{ $avis->links() }}
                </div>
            @else
                <p class="no-comments">Aucun commentaire pour le moment. Soyez le premier à commenter!</p>
            @endif
        </div>

        @if ($similarArticles->count() > 0)
            <div class="similar-articles">
                <h2>Articles similaires</h2>
                <div class="articles-grid flex justify-center flex-wrap gap-[2.5rem] w-full mt-16">

                    @foreach($similarArticles as $article)
                        <x-cards.article-card :article="$article" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

<style>
    .custom-audio-player {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        border-radius: 8px;
        height: 75%;
        max-width: 100%;
    }

    #seek-bar {
        flex: 1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const audio = document.getElementById('audio');
        const playPause = document.getElementById('play-pause');
        const playImg = playPause.querySelector('img');
        const seekBar = document.getElementById('seek-bar');
        const currentTime = document.getElementById('current-time');
        playPause.addEventListener('click', function () {
            if (audio.paused) {
                audio.play();
                playImg.src = "{{ asset('images/Stop.png') }}";
            } else {
                audio.pause();
                playImg.src = "{{ asset('images/Play.png') }}";
            }
        });

        audio.addEventListener('timeupdate', function () {
            seekBar.value = Math.floor(audio.currentTime);
            currentTime.textContent = formatTime(audio.currentTime);
        });

        seekBar.addEventListener('input', function () {
            audio.currentTime = seekBar.value;
        });

        audio.addEventListener('ended', function () {
            playImg.src = "{{ asset('images/Play.png') }}";
        });

        function formatTime(seconds) {
            const min = Math.floor(seconds / 60);
            const sec = Math.floor(seconds % 60).toString().padStart(2, '0');
            return `${min}:${sec}`;
        }
    });
</script>