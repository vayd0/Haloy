@props(['userId', 'isFollowing' => false])

@auth
    <form action="{{ route('users.follow', $userId) }}" method="POST" class="follow-form">
        @csrf
        @if($isFollowing)
            <button type="submit" class="relative btn btn-following w-full glass-morph py-2 px-4 transition-all duration-300 hover:rotate-[2deg] hover:scale-105"
                name="action" value="unfollow"> <span
                    class="absolute top-[-1rem] right-[-1rem] stat-icon mr-2 text-[0.75rem] p-1.5 glass-morph z-[50]"><i
                        class="fa-solid fa-plus"></i></span>Désabonner</button>
        @else
            <button type="submit"
                class="relative btn btn-follow w-full glass-morph text-white py-2 px-4 transition-all duration-300 hover:rotate-[2deg] hover:scale-105">
                <span class="absolute top-[-1rem] right-[-1rem] stat-icon mr-2 text-[0.75rem] p-1.5 glass-morph z-[50]"><i
                        class="fa-solid fa-plus"></i></span>Suivre
            </button>
        @endif
    </form>
@else
    <a href="{{ route('login') }}"
        class="w-full inline-block text-center bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Connectez-vous
        pour suivre</a>
@endauth