<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{isset($title) ? $title : "Page en cours"}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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