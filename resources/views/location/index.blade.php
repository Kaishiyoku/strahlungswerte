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

    <x-card class="overflow-y-hidden divide-y divide-gray-100 dark:divide-gray-800">
        @foreach ($locations as $location)
            <a href="{{ route('locations.show', $location) }}" class="group block md:flex md:justify-between md:items-start md:space-x-4 px-4 py-3 transition ease-out duration-300 hover:bg-blue-500 focus:outline-none focus:text-white focus:bg-blue-600 transition">
                <div>
                    <div class="group-hover:text-white text-2xl md:text-base">
                        {{ $location->postal_code }} {{ $location->name }}
                    </div>

                    <div class="hidden md:block group-hover:text-gray-300 w-full group-focus:text-gray-200 md:flex md:justify-between md:space-x-2 text-muted md:text-xs pt-2 md:pt-0">
                        {{ $location->height }}m
                    </div>
                </div>

                <div class="group-hover:text-white group-focus:text-white pt-2 md:pt-0">
                    <div>
                        @if ($location->status->name === 'operational')
                            {{ formatDecimal($location->last_measured_one_hour_value) }}µSv/h
                        @else
                            /
                        @endif
                    </div>

                    <div class="group-hover:text-gray-300 w-full group-focus:text-gray-200 md:flex md:justify-between md:space-x-2 text-muted md:text-xs pt-2 md:pt-0">
                        {{ formatStatus($location->status) }}
                    </div>
                </div>
            </a>
        @endforeach
    </x-card>

    <div class="mt-4">
        {{ $locations->links() }}
    </div>

    @include('shared._odl_copyright_notice')
@endsection
