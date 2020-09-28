@extends('layouts.app')

@section('title', __('statistic.index.title'))

@section('content')
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>@lang('validation.attributes.date')</th>
                    <th>@lang('validation.attributes.number_of_operational_locations')</th>
                    <th>@lang('validation.attributes.average_value')</th>
                    <th>@lang('validation.attributes.min_location_uuid')</th>
                    <th>@lang('validation.attributes.min_value')</th>
                    <th>@lang('validation.attributes.max_location_uuid')</th>
                    <th>@lang('validation.attributes.max_value')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statistics as $statistic)
                    <tr>
                        <td>{{ $statistic->date->format(__('common.date_formats.date')) }}</td>
                        <td>{{ $statistic->number_of_operational_locations }}</td>
                        <td>{{ $statistic->average_value }}µSv/h</td>
                        <td>{{ Html::linkRoute('locations.show', $statistic->minLocation->name, toSlug($statistic->minLocation->uuid, $statistic->minLocation->name)) }}</td>
                        <td>{{ $statistic->min_value }}µSv/h</td>
                        <td>{{ Html::linkRoute('locations.show', $statistic->maxLocation->name, toSlug($statistic->maxLocation->uuid, $statistic->maxLocation->name)) }}</td>
                        <td>{{ $statistic->max_value }}µSv/h</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $statistics->links() }}
    </div>

    @include('shared._odl_copyright_notice')
@endsection
