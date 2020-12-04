@extends('layouts.app')

@section('title', __('update_log.show.title', ['date' => $updateLog->created_at->format(__('common.date_formats.datetime'))]))

@include('shared._breadcrumbs', ['params' => $updateLog])

@section('content')
    <h1 class="text-4xl pb-4">
        {{ __('update_log.show.title_short') }}
        <small class="text-gray-500">{{ $updateLog->created_at->format(__('common.date_formats.datetime')) }}</small>
    </h1>

    <div class="card p-4">
        <div class="md:flex py-2 border-b border-gray-200 dark:border-gray-800">
            <div class="md:inline-block md:w-1/4">{{ __('validation.attributes.is_successful') }}:</div>
            <div class="md:inline-block md:w-3/4">{{ formatBoolean($updateLog->is_successful) }}</div>
        </div>

        <div class="md:flex py-2 border-b border-gray-200 dark:border-gray-800">
            <div class="inline-block w-1/4">{{ __('validation.attributes.command_name') }}:</div>
            <div class="inline-block w-3/4">{{ $updateLog->command_name }}</div>
        </div>

        <div class="md:flex py-2 border-b border-gray-200 dark:border-gray-800">
            <div class="inline-block w-1/4">{{ __('validation.attributes.number_of_new_entries') }}:</div>
            <div class="inline-block w-3/4">{{ $updateLog->number_of_new_entries }}</div>
        </div>

        <div class="{{ classNames('md:flex py-2', ['border-b border-gray-200' => !!$updateLog->json_content]) }}">
            <div class="inline-block w-1/4">{{ __('validation.attributes.duration_in_seconds') }}:</div>
            <div class="inline-block w-3/4">{{ $updateLog->duration_in_seconds }}</div>
        </div>

        @if ($updateLog->json_content)
            <div class="md:flex py-2">
                <div class="inline-block w-1/4">{{ __('validation.attributes.json_content') }}:</div>
                <div class="inline-block w-3/4">
                    <pre>{{ $updateLog->json_content }}</pre>
                </div>
            </div>
        @endif
    </div>
@endsection
