@props(['articleId', 'userLikeStatus' => null])

@auth
    <div class="like-actions">
        <form action="{{ route('article.like', $articleId) }}" method="POST" class="like-form" style="display:inline">
            @csrf
            <input type="hidden" name="nature" value="1">
            <button type="submit" class="btn btn-like {{ $userLikeStatus === true ? 'active' : '' }}">
                👍 J'aime
            </button>
        </form>

        <form action="{{ route('article.dislike', $articleId) }}" method="POST" class="like-form" style="display:inline; margin-left:8px;">
            @csrf
            <input type="hidden" name="nature" value="0">
            <button type="submit" class="btn btn-dislike {{ $userLikeStatus === false ? 'active' : '' }}">
                👎 Je n'aime pas
            </button>
        </form>

        @if ($userLikeStatus !== null)
            <form action="{{ route('article.unlike', $articleId) }}" method="POST" class="like-form" style="display:inline; margin-left:8px;">
                @csrf
                <button type="submit" class="btn btn-unlike">
                    ✕ Retirer
                </button>
            </form>
        @endif
    </div>
@else
    <div class="login-prompt">
        <p><a href="{{ route('login') }}">Connectez-vous</a> pour voter sur cet article</p>
    </div>
@endauth

