@if (isset($title))
    <title>{{ $title }} - {{ config('app.name', 'VPN Server') }}</title>
@else
    <title>{{ config('app.name', 'VPN Server') }}</title>
@endif
