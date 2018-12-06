@extends('layouts.app')

@section('title', __('location.show.title', ['postal_code' => $location->postal_code, 'name' => $location->name]))

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
                </tbody>
            </table>
        </div>
    </div>
@endsection