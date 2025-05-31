@extends('settings.setting')

@section('form')
    <div class="flex flex-col md:flex-row gap-4 items-start p-4 bg-gray-100 rounded-md shadow">
        <div class="w-full md:w-1/4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Queue Settings') }}
            </h2>
        </div>

        <div class="w-full md:w-3/4 space-y-6">
            {{-- Default Queue --}}
            <div class="p-4 border border-gray-300 rounded-lg bg-white shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">{{ __('Default Queue') }}</h3>
                <select name="queue[default]" id="queue_selector"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                    @foreach (['database', 'beanstalkd', 'sqs', 'redis'] as $driver)
                        <option value="{{ $driver }}" {{ config('queue.default') == $driver ? 'selected' : '' }}>
                            {{ ucfirst($driver) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Database Queue --}}
            <div id="database_queue"
                class="queue-config p-4 border border-green-300 rounded-lg bg-green-50 shadow-md hidden">
                <h3 class="text-lg font-semibold text-green-700 mb-4 border-b pb-2">{{ __('Database Queue') }}</h3>
                @foreach (['table', 'queue', 'retry_after', 'after_commit'] as $key)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                        </label>
                        <input type="text" name="queue[connections][database][{{ $key }}]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200"
                            value="{{ config('queue.connections.database.' . $key) }}">
                    </div>
                @endforeach
            </div>

            {{-- Beanstalkd Queue --}}
            <div id="beanstalkd_queue"
                class="queue-config p-4 border border-yellow-300 rounded-lg bg-yellow-50 shadow-md hidden">
                <h3 class="text-lg font-semibold text-yellow-700 mb-4 border-b pb-2">{{ __('Beanstalkd Queue') }}</h3>
                @foreach (['host', 'queue', 'retry_after', 'block_for', 'after_commit'] as $key)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                        </label>
                        <input type="text" name="queue[connections][beanstalkd][{{ $key }}]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-yellow-200"
                            value="{{ config('queue.connections.beanstalkd.' . $key) }}">
                    </div>
                @endforeach
            </div>

            {{-- SQS Queue --}}
            <div id="sqs_queue" class="queue-config p-4 border border-blue-300 rounded-lg bg-blue-50 shadow-md hidden">
                <h3 class="text-lg font-semibold text-blue-700 mb-4 border-b pb-2">{{ __('AWS SQS Queue') }}</h3>
                @foreach (['key', 'secret', 'prefix', 'queue', 'suffix', 'region', 'after_commit'] as $key)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                        </label>
                        <input type="text" name="queue[connections][sqs][{{ $key }}]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
                            value="{{ config('queue.connections.sqs.' . $key) }}">
                    </div>
                @endforeach
            </div>

            {{-- Redis Queue --}}
            <div id="redis_queue" class="queue-config p-4 border border-red-300 rounded-lg bg-red-50 shadow-md hidden">
                <h3 class="text-lg font-semibold text-red-700 mb-4 border-b pb-2">{{ __('Redis Queue') }}</h3>
                @foreach (['connection', 'queue', 'retry_after', 'block_for', 'after_commit'] as $key)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                        </label>
                        <input type="text" name="queue[connections][redis][{{ $key }}]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-red-200"
                            value="{{ config('queue.connections.redis.' . $key) }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const selector = document.getElementById('queue_selector');
            const configs = document.querySelectorAll('.queue-config');

            function updateVisibility() {
                configs.forEach(config => config.classList.add('hidden'));
                const selectedQueue = selector.value + '_queue';
                const activeConfig = document.getElementById(selectedQueue);
                if (activeConfig) activeConfig.classList.remove('hidden');
            }

            selector.addEventListener('change', updateVisibility);
            updateVisibility();
        });
    </script>
@endpush
