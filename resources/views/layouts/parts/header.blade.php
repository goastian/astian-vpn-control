<header class="bg-blue-500 text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center py-4 px-2">
        <h1 class="text-2xl font-bold">{{ config('app.name') }}</h1>
        <nav>
            <ul class="flex space-x-6">
                <li>
                    <a href="{{ route('login') }}" class="hover:underline">
                        <i class="mdi mdi-login"></i> {{ __('Login') }}
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>
