<!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}" defer>
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.css') }}" defer>
   <!-- <link rel="stylesheet" href="{{ asset('css/tooltip.css') }}" defer>-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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