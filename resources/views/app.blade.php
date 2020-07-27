<!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preload" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" as="style" crossorigin>
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="preload" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" as="style" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" as="style" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Poppins" as="style" crossorigin>
    <link rel="preload" href="{{ mix('js/app.js') }}" as="script">
    <link rel="preload" href="{{ asset('js/lazysizes.js') }}" as="script">
    <link rel="preload" href="//{{Request::getHost()}}:6001/socket.io/socket.io.js" as="script">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" defer>
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
    <body>
        <div id="app">
            <layout-component></layout-component>
        </div>
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="{{ asset('js/lazysizes.js') }}" defer></script>
    </body>
</html>