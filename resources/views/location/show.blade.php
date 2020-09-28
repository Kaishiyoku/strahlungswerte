@extends('layouts.app')

@section('title', __('location.show.title', ['postal_code' => $location->postal_code, 'name' => $location->name]))

@include('shared._breadcrumbs', ['params' => $location])

@section('content')
    <h1 class="text-4xl pb-4">
        {{ $location->postal_code }} {{ $location->name }}
    </h1>

    <div class="lg:grid lg:grid-cols-2 lg:gap-4">
        <div class="card p-4">
            <div class="md:flex py-2 border-b border-gray-200">
                <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.height') }}:</div>
                <div class="md:inline-block md:w-4/6">{{ $location->height }}m</div>
            </div>

            <div class="md:flex py-2 border-b border-gray-200">
                <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.last_measured_one_hour_value') }}:</div>
                <div class="md:inline-block md:w-4/6">{{ $location->last_measured_one_hour_value }}µSv/h</div>
            </div>

            <div class="md:flex py-2 border-b border-gray-200">
                <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.longitude') }}:</div>
                <div class="md:inline-block md:w-4/6">{{ $location->longitude }}</div>
            </div>

            <div class="md:flex py-2 border-b border-gray-200">
                <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.latitude') }}:</div>
                <div class="md:inline-block md:w-4/6">{{ $location->latitude }}</div>
            </div>

            <div class="md:flex py-2 border-b border-gray-200">
                <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.status_id') }}:</div>
                <div class="md:inline-block md:w-4/6">{{ formatStatus($location->status) }}</div>
            </div>

            <div class="md:flex py-2">
                <div class="md:inline-block md:w-2/6">{{ __('validation.attributes.measurement_node_id') }}:</div>
                <div class="md:inline-block md:w-4/6">{{ $location->measurementNode->name }}</div>
            </div>

            @include('shared._odl_copyright_notice')
        </div>

        <div>
            {{-- TODO: Map --}}

            {{--@map([
                'lat' => $location->latitude,
                'lng' => $location->longitude,
                'zoom' => 14,
                'markers' => [
                    [
                        'title' => "{$location->postal_code} {$location->name}",
                        'lat' => $location->latitude,
                        'lng' => $location->longitude,
                    ],
                ],
            ])--}}

            {{--@mapstyles--}}

            {{--@mapscripts--}}
        </div>
    </div>

    <div class="card mt-8">
        {!! $hourlyMeasurementsChart->assets() !!}
        {!! $hourlyMeasurementsChart->render() !!}
    </div>

    <div class="card mt-8">
        {!! $dailyMeasurementsChart->render() !!}
    </div>

    <h4 class="text-2xl mt-6 mb-4">{{ __('location.show.daily_values') }}</h4>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('validation.attributes.date') }}</th>
                    <th class="text-right">{{ __('validation.attributes.value') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dailyMeasurements->orderBy('date', 'desc')->get() as $dailyMeasurement)
                    <tr>
                        <td>{{ $dailyMeasurement->date->format(l('date')) }}</td>
                        <td class="text-right">{{ $dailyMeasurement->value }} µSv/h</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h4 class="text-2xl mt-6 mb-4">{{ __('location.show.hourly_values') }}</h4>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('validation.attributes.date') }}</th>
                    <th class="text-right">{{ __('validation.attributes.value') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hourlyMeasurements->orderBy('date', 'desc')->get() as $hourlyMeasurement)
                    <tr>
                        <td>{{ $hourlyMeasurement->date->format(l('datetime')) }}</td>
                        <td class="text-right">{{ $hourlyMeasurement->value }} µSv/h</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
