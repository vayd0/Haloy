@extends("layout.app")

@section('contenu')

    <form action="{{route("login")}}" method="post">
        @csrf
        <input class="p-4 py-2 glass-morph" type="email" name="email" required placeholder="Email" /><br />
        <input class="p-4 py-2 glass-morph" type="password" name="password" required placeholder="password" /><br />
        Remember me<input type="checkbox" name="remember"   /><br />
        <input type="submit" /><br />
    </form>
@endsection