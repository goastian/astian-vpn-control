@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-6 bg-gray-50 rounded-lg shadow-sm border border-gray-200">

        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Payment settings') }}
            </h2>
        </div>

        <div class="w-full md:w-3/4 space-y-4">

            <div class="mb-4 px-2 py-2">
                <label for="free" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('VPN free') }}
                </label>
                <input type="number" name="vpn[free]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    value="{{ config('vpn.free') }}">
            </div>


            <div class="mb-4 px-2 py-2">
                <label for="free" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('VPN basic') }}
                </label>
                <input type="number" name="vpn[basic]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    value="{{ config('vpn.basic') }}">
            </div>


            <div class="mb-4 px-2 py-2">
                <label for="free" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('VPN intermediate') }}
                </label>
                <input type="number" name="vpn[intermediate]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    value="{{ config('vpn.intermediate') }}">
            </div>


            <div class="mb-4 px-2 py-2">
                <label for="free" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('VPN advanced') }}
                </label>
                <input type="number" name="vpn[advanced]"
                    class="block w-full px-2 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    value="{{ config('vpn.advanced') }}">
            </div>
        </div>
    </div>
@endsection
