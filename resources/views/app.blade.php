<!doctype html>
<html lang="fr">
<head>
<!-- Google Tag Manager -->
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="title" content="Chuushin no Fansub">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Mathioris" />
    <meta name="copyright" content="Mathioris" />
    <meta name="description" content="Notre équipe de passionnés vous propose divers projets venant du Japon ! Animes, Scan, Light Novel ou Visual Novel : venez partager la culture japonaise !"/>
    <Meta name="keywords" content="Chuushin, Chuushin no fansub, Fansub, Scantrad, Light novel, visual novel, visual, light, novel, Vostfr, dl, manga, mangas, téléchargement, telechargement, serie, japon, anime, animes, scan, lecture en ligne, lecture">
    <link rel="icon" type="image/png" href="/img/Clogo.png" />
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.chuushin-no-fansub.fr/">
    <meta property="og:title" content="Chuushin no Fansub">
    <meta property="og:description" content="Notre équipe de passionnés vous propose divers projets venant du Japon ! Animes, Scan, Light Novel ou Visual Novel : venez partager la culture japonaise !">
    <meta property="og:image" content="/img/Clogo.png">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://www.chuushin-no-fansub.fr/">
    <meta property="twitter:title" content="Chuushin no Fansub">
    <meta property="twitter:description" content="https://www.chuushin-no-fansub.fr/">
    <meta property="twitter:image" content="/img/Clogo.png">
    <link rel="preload" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" as="style" crossorigin>
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="preload" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" as="style" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" as="style" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Poppins" as="style" crossorigin>
    <link rel="preload" href="{{ mix('js/app.js') }}" as="script">
    <link rel="preload" href="{{ asset('js/lazysizes.js') }}" as="script">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" defer>
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
    <body>
        <div id="app">
            <index-component></index-component>
        </div>
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="{{ asset('js/lazysizes.js') }}" defer></script>
    </body>
</html>