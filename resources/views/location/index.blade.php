@extends('layouts.app')

@section('title', __('location.index.title'))

@section('content')
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>@lang('validation.attributes.name')</th>
                <th>@lang('validation.attributes.status_id')</th>
                <th>@lang('validation.attributes.last_measured_one_hour_value')</th>
                <th>@lang('validation.attributes.height')</th>
                <th>@lang('validation.attributes.longitude')</th>
                <th>@lang('validation.attributes.latitude')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($locations as $location)
                <tr>
                    <td>{{ Html::linkRoute('locations.show', $location->name, toSlug($location->uuid, $location->name)) }}</td>
                    <td>{{ $location->status->name }}</td>
                    <td>{{ $location->last_measured_one_hour_value }}</td>
                    <td>{{ $location->height }}</td>
                    <td>{{ $location->longitude }}</td>
                    <td>{{ $location->latitude }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $locations->links() }}
@endsection