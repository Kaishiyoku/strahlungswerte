@extends('layouts.app')

@section('title', __('update_log.show.title', ['date' => $updateLog->created_at->format(__('common.date_formats.datetime'))]))

@section('content')
    <h1>
        {{ __('update_log.show.title_short') }}
        <small class="text-muted">{{ $updateLog->created_at->format(__('common.date_formats.datetime')) }}</small>
    </h1>

    <div class="row">
        <div class="col-3">{{ __('validation.attributes.is_successful') }}:</div>
        <div class="col-9">{{ formatBoolean($updateLog->is_successful) }}</div>
    </div>

    <div class="row">
        <div class="col-3">{{ __('validation.attributes.command_name') }}:</div>
        <div class="col-9">{{ $updateLog->command_name }}</div>
    </div>

    <div class="row">
        <div class="col-3">{{ __('validation.attributes.number_of_new_entries') }}:</div>
        <div class="col-9">{{ $updateLog->number_of_new_entries }}</div>
    </div>

    <div class="row">
        <div class="col-3">{{ __('validation.attributes.duration_in_seconds') }}:</div>
        <div class="col-9">{{ $updateLog->duration_in_seconds }}</div>
    </div>

    <div class="row">
        <div class="col-3">{{ __('validation.attributes.json_content') }}:</div>
        <div class="col-9">
            @if ($updateLog->json_content)
                <pre>{{ $updateLog->json_content }}</pre>
            @endif
        </div>
    </div>
@endsection
