@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-2 items-start md:space-x-6 p-4 bg-gray-100 rounded-md shadow">

        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('User settings') }}
            </h2>
        </div>

        <div class="w-full md:w-3/4">
            <div class="mb-4 px-2 py-2">

            </div>
        </div>
    </div>
@endsection
