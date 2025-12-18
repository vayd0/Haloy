<div class="relative">
    <button type="button" class="flex items-center gap-2 px-4 py-2 glass-morph rounded hover:bg-white/20 transition"
        id="dropdown-toggle">
        <div class="flex justify-between items-center gap-2">
            <x-avatar :user="Auth::user()->name"/>
            {{ Auth::user()->name }}
        </div>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div id="dropdown-menu"
        class="absolute glass-morph left-0 mt-2 w-40 rounded shadow-lg py-2 opacity-0 pointer-events-none transition-all z-50" style="border-radius:10px;">
        <a href="{{ route('user.profile') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
        <a href="{{ route('logout') }}" onclick="document.getElementById('logout').submit(); return false;"
            class="block px-4 py-2 hover:bg-gray-100 text-redc">Déconnexion</a>
        <form id="logout" action="{{ route('logout') }}" method="post" class="hidden">
            @csrf
        </form>
    </div>
</div>