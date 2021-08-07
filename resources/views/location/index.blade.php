@extends('layouts.app')

@section('title', __('location.index.title'))

@section('content')
    {{ html()->form('GET', route('locations.index'))->class('pb-4')->open() }}
        <div class="flex">
            <x-input id="term" class="block w-full rounded-r-none border-r-0" type="text" name="term" :value="request()->get('term')" :placeholder="__('common.search_term')"/>

            <x-secondary-button class="rounded-l-none">
                {{ __('location.search') }}
            </x-secondary-button>
        </div>
    {{ html()->form()->close() }}

    <div class="card mt-4">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="hidden md:table-cell">{{ __('validation.attributes.postal_code') }}</th>
                    <th>{{ __('validation.attributes.name') }}</th>
                    <th class="hidden md:table-cell">{{ __('validation.attributes.status_id') }}</th>
                    <th class="text-right">{{ __('validation.attributes.last_measured_one_hour_value') }}</th>
                    <th class="hidden md:table-cell text-right">{{ __('validation.attributes.height') }}</th>
                    <th class="hidden md:table-cell text-right">{{ __('validation.attributes.longitude') }}</th>
                    <th class="hidden md:table-cell text-right">{{ __('validation.attributes.latitude') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($locations as $location)
                    <tr>
                        <td class="hidden md:table-cell">{{ $location->postal_code }}</td>
                        <td>
                            <div class="md:hidden">{{ $location->postal_code }}</div>
                            <div>{{ html()->a(route('locations.show', $location), $location->name)->class('link') }}</div>
                            <div class="md:hidden mt-2 text-sm text-muted">{{ formatStatus($location->status) }}</div>
                        </td>
                        <td class="hidden md:table-cell">{{ formatStatus($location->status) }}</td>
                        <td class="text-right">
                            @if ($location->status->name === 'operational')
                                {{ formatDecimal($location->last_measured_one_hour_value) }}µSv/h
                            @else
                                /
                            @endif
                        </td>
                        <td class="hidden md:table-cell text-right">{{ $location->height }}m</td>
                        <td class="hidden md:table-cell text-right">{{ formatDecimal($location->longitude) }}</td>
                        <td class="hidden md:table-cell text-right">{{ formatDecimal($location->latitude) }}</td>
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
