<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    {{-- <link nonce="{{ $nonce }}" rel="preconnect" href="https://fonts.bunny.net"> --}}
    {{-- <link nonce="{{ $nonce }}" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/tailwind.css') }}">
</head>

<body>
    <div id="app"></div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
