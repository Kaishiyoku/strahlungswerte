@extends('layouts.app')

@section('title', __('location.index.title'))

@section('content')
    {{ Form::open(['route' => 'locations.index', 'method' => 'get', 'class' => 'form-inline']) }}
        <div class="form-group mx-sm-3 mb-2">
            {{ Form::label('term', __('validation.attributes.name'), ['class' => 'sr-only']) }}

            {{ Form::text('term', request()->get('term'), ['class' => 'form-control', 'placeholder' => __('validation.attributes.name')]) }}
        </div>

        {{ Form::button(__('location.search'), ['type' => 'submit', 'class' => 'btn btn-primary mb-2']) }}
        &nbsp;
        @if (!empty(request()->get('term')))
            {!! Html::decode(Html::linkRoute('locations.index', '<i class="fas fa-times"></i>', [], ['class' => 'btn btn-danger mb-2'])) !!}
        @endif
    {{ Form::close() }}

    <table class="table table-striped table-sm mt-2">
        <thead>
            <tr>
                <th>@lang('validation.attributes.postal_code')</th>
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
                    <td>{{ $location->postal_code }}</td>
                    <td>{{ Html::linkRoute('locations.show', $location->name, toSlug($location->uuid, $location->name)) }}</td>
                    <td>{{ formatStatus($location->status) }}</td>
                    <td>{{ $location->last_measured_one_hour_value }}µSv/h</td>
                    <td>{{ $location->height }}m</td>
                    <td>{{ $location->longitude }}</td>
                    <td>{{ $location->latitude }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $locations->links() }}

    @include('shared._odl_copyright_notice')
@endsection
