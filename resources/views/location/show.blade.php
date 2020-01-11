@extends('layouts.app')

@section('title', __('location.show.title', ['postal_code' => $location->postal_code, 'name' => $location->name]))

@section('breadcrumbs')
    {!! Breadcrumbs::render('locations.show', $location) !!}
@endsection

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
                        <td>{{ $location->last_measured_one_hour_value }} µSv/h</td>
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
        </div>

        <div class="col-lg-6">
            @map([
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
            ])

        </div>
    </div>

    <div class="mt-5">
        {!! $hourlyMeasurementsChart->container() !!}
        {!! $hourlyMeasurementsChart->script() !!}
    </div>

    <div>
        {!! $dailyMeasurementsChart->container() !!}
        {!! $dailyMeasurementsChart->script() !!}
    </div>
@endsection
