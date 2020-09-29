@extends('layouts.app')

@section('title', __('location.index.title'))

@section('content')
    {{ Form::open(['route' => 'locations.index', 'method' => 'get', 'class' => 'form-inline']) }}
        <div class="flex">
            {{ Form::text('term', request()->get('term'), ['class' => 'input-with-btn', 'placeholder' => __('validation.attributes.name')]) }}

            {{ Form::button(__('location.search'), ['type' => 'submit', 'class' => 'btn-with-input']) }}

            @if (!empty(request()->get('term')))
                <div class="input-group-append">
                    {!! Html::decode(Html::linkRoute('locations.index', '<i class="fas fa-times"></i>', [], ['class' => 'btn btn-danger mb-2'])) !!}
                </div>
            @endif
        </div>
    {{ Form::close() }}

    <div class="card mt-4">
        <table class="table">
            <thead>
                <tr>
                    <th>@lang('validation.attributes.postal_code')</th>
                    <th>@lang('validation.attributes.name')</th>
                    <th>@lang('validation.attributes.status_id')</th>
                    <th class="text-right">@lang('validation.attributes.last_measured_one_hour_value')</th>
                    <th class="text-right">@lang('validation.attributes.height')</th>
                    <th class="text-right">@lang('validation.attributes.longitude')</th>
                    <th class="text-right">@lang('validation.attributes.latitude')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($locations as $location)
                    <tr>
                        <td>{{ $location->postal_code }}</td>
                        <td>{{ Html::linkRoute('locations.show', $location->name, toSlug($location->uuid, $location->name)) }}</td>
                        <td>{{ formatStatus($location->status) }}</td>
                        <td class="text-right">
                            @if ($location->status->name === 'operational')
                                {{ formatDecimal($location->last_measured_one_hour_value) }}µSv/h
                            @else
                                /
                            @endif
                        </td>
                        <td class="text-right">{{ $location->height }}m</td>
                        <td class="text-right">{{ formatDecimal($location->longitude) }}</td>
                        <td class="text-right">{{ formatDecimal($location->latitude) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $locations->links() }}
    </div>

    @include('shared._odl_copyright_notice')
@endsection
