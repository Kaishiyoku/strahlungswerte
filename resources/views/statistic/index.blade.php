@extends('layouts.app')

@section('title', __('statistic.index.title'))

@section('content')
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>@lang('validation.attributes.date')</th>
                    <th class="text-right">@lang('validation.attributes.number_of_operational_locations')</th>
                    <th class="text-right">@lang('validation.attributes.average_value')</th>
                    <th>@lang('validation.attributes.min_location_uuid')</th>
                    <th class="text-right">@lang('validation.attributes.min_value')</th>
                    <th>@lang('validation.attributes.max_location_uuid')</th>
                    <th class="text-right">@lang('validation.attributes.max_value')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statistics as $statistic)
                    <tr>
                        <td>{{ $statistic->date->format(__('common.date_formats.date')) }}</td>
                        <td class="text-right">{{ $statistic->number_of_operational_locations }}</td>
                        <td class="text-right">{{ formatDecimal($statistic->average_value) }}µSv/h</td>
                        <td>{{ html()->a(route('locations.show', $statistic->minLocation), $statistic->minLocation->name)->class('link') }}</td>
                        <td class="text-right">{{ formatDecimal($statistic->min_value) }}µSv/h</td>
                        <td>{{ html()->a(route('locations.show', $statistic->maxLocation), $statistic->maxLocation->name)->class('link') }}</td>
                        <td class="text-right">{{ formatDecimal($statistic->max_value) }}µSv/h</td>
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
