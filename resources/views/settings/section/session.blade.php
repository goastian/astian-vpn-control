@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-6 bg-gray-50 rounded-lg shadow-sm border border-gray-200">

        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Session settings') }}
            </h2>
        </div>

        <div class="w-full md:w-3/4 space-y-4">

            <div class="p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-sm">
                <label for="">Driver</label>
                <select name="session[driver]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="" disabled>{{ __('Choose option') }}</option>
                    <option value="file" {{ config('session.driver') == 'file' ? 'selected' : '' }}>
                        file
                    </option>
                    <option value="cookie" {{ config('session.driver') == 'cookie' ? 'selected' : '' }}>
                        cookie
                    </option>
                    <option value="database" {{ config('session.driver') == 'database' ? 'selected' : '' }}>
                        database
                    </option>
                    <option value="apc" {{ config('session.driver') == 'apc' ? 'selected' : '' }}>
                        apc
                    </option>
                    <option value="memcached" {{ config('session.driver') == 'memcached' ? 'selected' : '' }}>
                        memcached
                    </option>
                    <option value="redis" {{ config('session.driver') == 'redis' ? 'selected' : '' }}>
                        redis
                    </option>
                    <option value="dynamodb" {{ config('session.driver') == 'dynamodb' ? 'selected' : '' }}>
                        dynamodb
                    </option>
                    <option value="array" {{ config('session.driver') == 'array' ? 'selected' : '' }}>
                        array
                    </option>
                </select>
            </div>

            <div class="p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-sm">
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Session Lifetime') }}</label>
                    <input type="text" name="session[table]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('session.table') }}">
                    <small
                        class="text-gray-500">{{ __('When using the "database" session driver, you may specify the table we should use to manage the sessions') }}</small>
                </div>
            </div>


            <div class="p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-sm">
                <h1 class="mb-2">{{ __('Cookie settings') }}</h1>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Session cookie name') }}</label>
                    <input type="text" name="session[cookie]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('session.cookie') }}">
                    <small class="text-gray-500">
                        {{ __('Here you may change the name of the cookie used to identify a session instance by ID') }}
                    </small>
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Session cookie csrf name') }}</label>
                    <input type="text" name="session[xcsrf-token]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('session.xcsrf-token') }}">
                    <small class="text-gray-500">
                        {{ __('Here you may change the name of the cookie used to identify a session instance by ID') }}
                    </small>
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Session Cookie Path') }}</label>
                    <input type="text" name="session[path]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        value="{{ config('session.path') }}">
                    <small class="text-gray-500">
                        {{ __('The session cookie path determines the path for which the cookie will be regarded as available') }}
                    </small>
                </div>
                <div class="mb-2">
                    <label for="">HTTPS Only Cookies</label>
                    <select name="session[secure]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose option') }}</option>
                        <option value="{{ true }}" {{ config('session.secure') == true ? 'selected' : '' }}>
                            {{ __('Yes') }}
                        </option>
                        <option value="{{ false }}" {{ config('session.secure') == false ? 'selected' : '' }}>
                            {{ __('No') }}
                        </option>
                    </select>
                    <small class="text-gray-500">
                        {{ __('By setting this option to true, session cookies will only be sent back to the server if the browser has a HTTPS connection') }}
                    </small>
                </div>

                <div class="mb-2">
                    <label for="">HTTP Access Only</label>
                    <select name="session[http_only]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose option') }}</option>
                        <option value="{{ true }}" {{ config('session.http_only') == true ? 'selected' : '' }}>
                            {{ __('Yes') }}
                        </option>
                        <option value="{{ false }}" {{ config('session.http_only') == false ? 'selected' : '' }}>
                            {{ __('No') }}
                        </option>
                    </select>
                    <small class="text-gray-500">
                        {{ __('Setting this value to true will prevent JavaScript from accessing the value of the cookie and the cookie will only be accessible through the HTTP protocol') }}
                    </small>
                </div>

                <div class="mb-2">
                    <label for="">Partitioned Cookies</label>
                    <select name="session[http_only]"
                        class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled>{{ __('Choose option') }}</option>
                        <option value="{{ true }}" {{ config('session.http_only') == true ? 'selected' : '' }}>
                            {{ __('Yes') }}
                        </option>
                        <option value="{{ false }}" {{ config('session.http_only') == false ? 'selected' : '' }}>
                            {{ __('No') }}
                        </option>
                    </select>
                    <small class="text-gray-500">
                        {{ __('Setting this value to true will tie the cookie to the top-level site for a cross-site context') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection
