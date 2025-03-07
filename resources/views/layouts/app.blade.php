<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @include('layouts.parts.meta')

    @include('layouts.parts.favicon')

    <title>{{ config('app.name', 'VPN Server') }}</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/tailwind.css') }}">
</head>

<body class="bg-gray-100 text-gray-800 flex flex-col justify-around min-h-screen">

    <div id="app" data-app-name="{{ config('app.name') }}"></div>

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
