<nav class="fixed top-0 left-0 right-0 mx-auto flex items-center justify-center px-8 py-4 text-white max-w-4xl z-30">
    <section class="flex items-center space-x-8">
        <a href="{{route('accueil')}}">Accueil</a>
        <a href="#">Contact</a>
    </section>

    <section class="flex-shrink-0 flex items-center justify-center">
        <img src="{{ asset('images/logo_black.png') }}" alt="Logo" class="h-14 w-14 mx-8">
    </section>

    <section class="flex items-center space-x-8">
        @auth
            <!--{{Auth::user()->name}}-->
            <a href="{{route('article.create')}}">Créer article</a>
            <a href="{{route("logout")}}" onclick="document.getElementById('logout').submit(); return false;">Logout</a>
            <form id="logout" action="{{route("logout")}}" method="post">
                @csrf
            </form>
        @else
            <a href="{{route("login")}}">Login</a>
            <a href="{{route("register")}}">Register</a>
        @endauth
    </section>
</nav>