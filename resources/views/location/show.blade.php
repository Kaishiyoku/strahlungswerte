@extends('layouts.app')

@section('title', __('location.show.title', ['postal_code' => $location->postal_code, 'name' => $location->name]))

@include('shared._breadcrumbs', ['params' => $location])

@section('content')
    <h1>
        {{ $location->postal_code }} {{ $location->name }}
    </h1>

    <div class="row">
        <div class="col-lg-6">
            <table class="table">
                <tbody>
                    <tr>
                        <td>@lang('validation.attributes.height'):</td>
                        <td>{{ $location->height }}m</td>
                    </tr>
                    <tr>
                        <td>@lang('validation.attributes.last_measured_one_hour_value'):</td>
                        <td>{{ $location->last_measured_one_hour_value }}µSv/h</td>
                    </tr>
                    <tr>
                        <td>@lang('validation.attributes.longitude'):</td>
                        <td>{{ $location->longitude }}</td>
                    </tr>
                    <tr>
                        <td>@lang('validation.attributes.latitude'):</td>
                        <td>{{ $location->latitude }}</td>
                    </tr>
                    <tr>
                        <td>@lang('validation.attributes.status_id'):</td>
                        <td>{{ formatStatus($location->status) }}</td>
                    </tr>
                    <tr>
                        <td>@lang('validation.attributes.measurement_node_id'):</td>
                        <td>{{ $location->measurementNode->name }}</td>
                    </tr>
                </tbody>
            </table>

            @include('shared._odl_copyright_notice')
        </div>

        <div class="col-lg-6">
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
        </div>
    </div>

    <div class="mt-5">
        {!! $hourlyMeasurementsChart->assets() !!}
        {!! $hourlyMeasurementsChart->render() !!}
    </div>

    <div class="mt-5">
        {!! $dailyMeasurementsChart->render() !!}
    </div>

    <div class="row">
        <div class="col-md-6">
            <h4 class="mt-5">{{ __('location.show.daily_values') }}</h4>

            <table class="table table-sm table-striped">
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

        <div class="col-md-6">
            <h4 class="mt-5">{{ __('location.show.hourly_values') }}</h4>

            <table class="table table-sm table-striped">
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
    </div>

{{--    @mapstyles--}}

{{--    @mapscripts--}}
@endsection
