@extends('layouts.app')

@section('title', __('statistic.index.title'))

@section('content')
    <x-card class="overflow-x-hidden divide-y divide-gray-100 dark:divide-gray-800">
        @foreach ($statistics as $statistic)
            <div class="block md:flex md:justify-between md:items-start md:space-x-4 px-4 py-3 transition ease-out duration-300 hover:bg-gray-50">
                <div>
                    <div class="text-2xl md:text-base">
                        {{ $statistic->date->format(__('common.date_formats.date')) }}
                    </div>

                    <div class="w-full group-focus:text-gray-200 text-muted md:text-sm pt-2 md:pt-0">
                        <div class="mb-2">
                            <div>{{ __('statistic.index.min_value', ['value' => formatDecimal($statistic->min_value)]) }}</div>
                            <div>{{ html()->a(route('locations.show', $statistic->minLocation), $statistic->minLocation->name)->class('link') }}</div>
                        </div>
                        <div>
                            <div>{{ __('statistic.index.max_value', ['value' => formatDecimal($statistic->max_value)]) }}</div>
                            <div>{{ html()->a(route('locations.show', $statistic->maxLocation), $statistic->maxLocation->name)->class('link') }}</div>
                        </div>
                    </div>
                </div>

                <div class="pt-2 md:pt-0">
                    <div class="text-muted md:text-black">
                        {{ trans_choice('statistic.index.number_of_operational_locations', $statistic->number_of_operational_locations) }}
                    </div>

                    <div class="w-full group-focus:text-gray-200 md:flex md:justify-between md:space-x-2 text-muted md:text-sm pt-2 md:pt-0">
                        {{ __('statistic.index.average_value', ['value' => formatDecimal($statistic->average_value)]) }}
                    </div>
                </div>
            </div>
        @endforeach
    </x-card>

    <div class="mt-4">
        {{ $statistics->links() }}
    </div>

    @include('shared._odl_copyright_notice')
@endsection
