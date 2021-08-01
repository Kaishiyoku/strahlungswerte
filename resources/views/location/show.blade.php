@extends('layouts.app')

@section('title', __('location.show.title', ['postal_code' => $location->postal_code, 'name' => $location->name]))

@include('shared._breadcrumbs', ['params' => $location])

@section('content')
    <h1 class="text-4xl pb-4">
        {{ $location->postal_code }} {{ $location->name }}
    </h1>

    <div class="lg:grid lg:grid-cols-2 lg:gap-4">
        <div class="card p-4 mb-8 flex flex-col justify-between">
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
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('validation.attributes.date') }}</th>
                                <th class="text-right">{{ __('validation.attributes.value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailyMeasurementsForTable as $dailyMeasurement)
                                <tr>
                                    <td>{{ $dailyMeasurement->date->format(l('date')) }}</td>
                                    <td class="text-right">{{ formatDecimal($dailyMeasurement->value) }} µSv/h</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-modal>

                <x-modal name="hourly_values_modal" :title="__('location.show.hourly_values')">
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('validation.attributes.date') }}</th>
                                <th class="text-right">{{ __('validation.attributes.value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hourlyMeasurementsForTable as $hourlyMeasurement)
                                <tr>
                                    <td>{{ $hourlyMeasurement->date->format(l('datetime')) }}</td>
                                    <td class="text-right">{{ formatDecimal($hourlyMeasurement->value) }} µSv/h</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-modal>
            </div>
        </div>

        <div class="card mb-8 flex justify-center">
            <a href="{{ getGoogleMapsUrlForLocation($location) }}" class="w-full">
                <img src="{{ getStaticMapUrlForLocation($location) }}" alt="map" class="w-full dark:img-invert"/>
            </a>
        </div>
    </div>

    @include('shared._odl_copyright_notice')

    <div id="hourly-measurements-chart" class="card mt-8 py-4"></div>

    <div id="daily-measurements-chart" class="card mt-8 py-4"></div>

    <script type="text/javascript">
        onDomReady(() => {
            renderChart('#hourly-measurements-chart', '{{ __('location.show.hourly_values') }}', @json($hourlyMeasurementsChartData), 350, ['#e83e8c', '#007bff'])
            renderChart('#daily-measurements-chart', '{{ __('location.show.daily_values') }}', @json($dailyMeasurementsChartData), 350, ['#6610f2'])
        });
    </script>
@endsection
