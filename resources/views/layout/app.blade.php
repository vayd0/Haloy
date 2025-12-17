<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{isset($title) ? $title : "Page en cours"}}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

@vite(["resources/css/normalize.css", 'resources/css/app.css', 'resources/js/app.js', '/resources/css/partials.css'])
</head>
<body>
<nav>
@include("partials.navbar")
</nav>

<main>
    @yield("contenu")
</main>

</body>
</html>
