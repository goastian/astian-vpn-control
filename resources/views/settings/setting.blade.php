<!-- resources/views/settings.blade.php -->
@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Settings')])
@endsection

@section('header')
    <nav class="bg-indigo-600 text-white py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="{{ route('user.dashboard') }}" class="text-lg font-semibold">
                <i class="mdi mdi-home text-2xl"></i>
                {{ __('Dashboard') }}
            </a>
        </div>
    </nav>
@endsection

@section('content')
    <div class="flex-grow mx-4 py-4">
        <div class="flex flex-col md:flex-row gap-4">

            <div class="flex-grow md:w-1/4 mx-0">
                <button id="menu-toggle"
                    class="md:hidden px-4 py-2 text-white bg-indigo-600 rounded-md shadow-md hover:bg-indigo-700 transition duration-300">
                    <i class="mdi mdi-menu text-2xl"></i>
                </button>

                <ul id="sidebar-menu"
                    class="hidden md:block w-full bg-white border border-gray-300 rounded-lg shadow-md divide-y divide-gray-200 md:mb-0 transition-all duration-300 ease-in-out">
                    @php
                        $routes = [
                            'admin.settings.general' => ['icon' => 'mdi-home', 'label' => __('General')],
                            'admin.settings.plans' => ['icon' => 'mdi-cash-sync', 'label' => __('Plans')],
                            'admin.settings.session' => ['icon' => 'mdi-cookie-settings', 'label' => __('Session')],
                            'admin.settings.user' => ['icon' => 'mdi-account-key', 'label' => __('User')],
                            'admin.settings.routes' => ['icon' => 'mdi-router-network', 'label' => __('Routes')],
                            'admin.settings.redis' => ['icon' => 'mdi-database-cog', 'label' => __('Redis')],
                            'admin.settings.queues' => [
                                'icon' => 'mdi-queue-first-in-last-out',
                                'label' => __('Queues'),
                            ],
                        ];
                    @endphp

                    @foreach ($routes as $route => $data)
                        <li
                            class="hover:bg-indigo-100 cursor-pointer transition duration-300 {{ request()->routeIs($route) ? 'bg-indigo-50 text-blue-500' : 'text-gray-700' }}">
                            <a class="px-4 py-3 flex items-center gap-2" href="{{ route($route) }}">
                                <i class="mdi {{ $data['icon'] }} text-2xl"></i>
                                <span class="font-medium">{{ $data['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="w-full md:w-3/4 h-full">
                <form action="{{ route('admin.settings.update') }}" method="post" autocomplete="off">
                    @method('put')
                    @csrf
                    <input type="hidden" name="current_route" value="{{ url()->current() }}">
                    <div class="max-h-[100vh] overflow-y-auto">
                        @yield('form')
                    </div>

                    <div class="my-4 flex items-center">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300">
                            <i class="mdi mdi-content-save-all"></i> {{ __('Save changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebarMenu = document.getElementById('sidebar-menu');

            menuToggle.addEventListener('click', () => {
                sidebarMenu.classList.toggle('hidden');
                sidebarMenu.classList.toggle('animate-slide-in');
            });
        });
    </script>
@endpush
