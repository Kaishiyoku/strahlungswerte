@extends('layouts.app')

@section('title', __('location.show.title', ['postal_code' => $location->postal_code, 'name' => $location->name]))

@include('shared._breadcrumbs', ['params' => $location])

@section('content')
    <h1 class="text-4xl pb-4">
        {{ $location->postal_code }} {{ $location->name }}
    </h1>

    <div class="lg:grid lg:grid-cols-2 lg:gap-4">
        <x-card class="p-4 mb-8 flex flex-col justify-between">
            <div>
                <div class="md:flex py-2 border-b border-gray-200 dark:border-gray-800">
                    <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.height') }}:</div>
                    <div class="md:inline-block md:w-4/6">{{ $location->height }}m</div>
                </div>

                <div class="md:flex py-2 border-b border-gray-200 dark:border-gray-800">
                    <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.last_measured_one_hour_value') }}:</div>
                    <div class="md:inline-block md:w-4/6">{{ formatDecimal($location->last_measured_one_hour_value) }}µSv/h</div>
                </div>

                <div class="md:flex py-2 border-b border-gray-200 dark:border-gray-800">
                    <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.longitude') }}:</div>
                    <div class="md:inline-block md:w-4/6">{{ formatDecimal($location->longitude) }}</div>
                </div>

                <div class="md:flex py-2 border-b border-gray-200 dark:border-gray-800">
                    <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.latitude') }}:</div>
                    <div class="md:inline-block md:w-4/6">{{ formatDecimal($location->latitude) }}</div>
                </div>

                <div class="md:flex py-2 border-b border-gray-200 dark:border-gray-800">
                    <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.status_id') }}:</div>
                    <div class="md:inline-block md:w-4/6">{{ formatStatus($location->status) }}</div>
                </div>

                <div class="md:flex py-2">
                    <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.measurement_node_id') }}:</div>
                    <div class="md:inline-block md:w-4/6">{{ $location->measurementNode->name }}</div>
                </div>
            </div>

            <div class="flex space-x-2 mt-8">
                <x-modal name="daily_values_modal" :title="__('location.show.daily_values')">
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($dailyMeasurementsForTable as $dailyMeasurement)
                            <div class="w-full flex justify-between px-2 py-1 hover:bg-gray-200 even:bg-gray-100 transition">
                                <div>{{ $dailyMeasurement->date->format(l('date')) }}</div>
                                <div class="text-right">{{ formatDecimal($dailyMeasurement->value) }} µSv/h</div>
                            </div>
                        @endforeach
                    </div>
                </x-modal>

                <x-modal name="hourly_values_modal" :title="__('location.show.hourly_values')">
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($hourlyMeasurementsForTable as $hourlyMeasurement)
                            <div class="w-full flex justify-between px-2 py-1 hover:bg-gray-200 even:bg-gray-100 transition">
                                <div>{{ $hourlyMeasurement->date->format(l('datetime')) }}</div>
                                <div class="text-right">{{ formatDecimal($hourlyMeasurement->value) }} µSv/h</div>
                            </div>
                        @endforeach
                    </div>
                </x-modal>
            </div>
        </x-card>

        <x-card class="mb-8 flex justify-center overflow-hidden">
            <a href="{{ getGoogleMapsUrlForLocation($location) }}" class="w-full">
                <img src="{{ getStaticMapUrlForLocation($location) }}" alt="map" class="w-full dark:img-invert"/>
            </a>
        </x-card>
    </div>

    @include('shared._odl_copyright_notice')

    <x-card id="hourly-measurements-chart" class="mt-8 py-4"/>

    <x-card id="daily-measurements-chart" class="mt-8 py-4"/>

    <script type="text/javascript">
        onDomReady(() => {
            renderChart('#hourly-measurements-chart', '{{ __('location.show.hourly_values') }}', @json($hourlyMeasurementsChartData), 350, ['#e83e8c', '#007bff'])
            renderChart('#daily-measurements-chart', '{{ __('location.show.daily_values') }}', @json($dailyMeasurementsChartData), 350, ['#6610f2'])
        });
    </script>
@endsection
