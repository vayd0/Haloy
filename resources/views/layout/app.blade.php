<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{isset($title) ? $title : "Page en cours"}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(["resources/css/normalize.css", 'resources/css/app.css', 'resources/js/app.js', 'resources/js/dropdown.js', 'resources/js/navbar.js'])
</head>

<body>
<div class="container mx-auto">
        @include("partials.navbar")
        @include("components.background")
        <main class="w-full mx-auto">
            @yield("contenu")
        </main>

        @include("partials.footer")
</div>
</body>

</html>