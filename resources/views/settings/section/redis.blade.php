@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-4 bg-gray-100 rounded-md shadow">

        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Redis Settings') }}
            </h2>
        </div>

        <div class="w-full md:w-3/4 space-y-6">
            {{-- Redis Default Config --}}
            <div class="p-4 border border-gray-300 rounded-lg bg-white shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    {{ __('Redis Default Configuration') }}
                </h3>

                <div class="grid gap-4">
                    @foreach (['url', 'host', 'username', 'password', 'port', 'database'] as $key)
                        <div>
                            <label for="database_redis_default_{{ $key }}"
                                class="block text-sm font-medium text-gray-700">
                                {{ __('Redis default :name', ['name' => $key]) }}
                            </label>
                            <input id="database.redis.default_{{ $key }}"
                                type="{{ $key === 'password' ? 'password' : ($key === 'port' || $key === 'database' ? 'number' : 'text') }}"
                                name="database[redis][default][{{ $key }}]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
                                placeholder="{{ __('Enter redis cache :name', ['name' => $key]) }}"
                                value="{{ config('database.redis.default.' . $key, $key === 'port' ? '6379' : ($key === 'database' ? 1 : '')) }}">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Redis Cache Config --}}
            <div class="p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-md">
                <h3 class="text-lg font-semibold text-blue-700 mb-4 border-b pb-2">
                    {{ __('Redis cache Configuration') }}
                </h3>

                <div class="grid gap-4">
                    @foreach (['url', 'host', 'username', 'password', 'port', 'database'] as $key)
                        <div>
                            <label for="database.redis.cache_{{ $key }}"
                                class="block text-sm font-medium text-gray-700">
                                {{ __('Redis default :name', ['name' => $key]) }}
                            </label>
                            <input id="database.redis.cache_{{ $key }}"
                                type="{{ $key === 'password' ? 'password' : ($key === 'port' || $key === 'database' ? 'number' : 'text') }}"
                                name="database[redis][cache][{{ $key }}]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
                                placeholder="{{ __('Enter redis cache :name', ['name' => $key]) }}"
                                value="{{ config('database.redis.cache.' . $key, $key === 'port' ? '6379' : ($key === 'database' ? 1 : '')) }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
