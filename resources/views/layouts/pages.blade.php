<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.parts.meta')

    @yield('title', config('app.name', 'OAuth2 Server'))

    @include('layouts.parts.favicon')

    
    <link rel="stylesheet" href="{{ mix('/css/pages.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/tailwind.css') }}">

    <!-- Notyf-->
    <link nonce={{ $nonce }} rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script nonce={{ $nonce }} src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>

    @stack('css')
</head>

<body class="bg-gray-100 text-gray-800 flex flex-col justify-around min-h-screen">

    @yield('header')

    @include('layouts.parts.alerts')

    @yield('content')

    @yield('footer')

    <script src="{{ mix('/js/pages.js') }}"></script>

    @stack('js')
    @stack('modals')

</body>

</html>
