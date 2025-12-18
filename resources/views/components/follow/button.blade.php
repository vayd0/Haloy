@props(['userId', 'isFollowing' => false])

@auth
    <form action="{{ route('users.follow', $userId) }}" method="POST" class="follow-form">
        @csrf
        @if($isFollowing)
            <button type="submit" class="btn btn-following w-full bg-gray-300 text-gray-800 py-2 px-4 rounded" name="action" value="unfollow">Se désabonner</button>
        @else
            <button type="submit" class="btn btn-follow w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Suivre</button>
        @endif
    </form>
@else
    <a href="{{ route('login') }}" class="w-full inline-block text-center bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Connectez-vous pour suivre</a>
@endauth