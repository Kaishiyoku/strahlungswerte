@extends('layouts.app')

@section('title', __('update_log.index.title'))

@section('content')
    <h1 class="text-4xl pb-4">{{ __('update_log.index.title') }}</h1>

    <div class="card">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>@lang('validation.attributes.created_at')</th>
                    <th>@lang('validation.attributes.is_successful')</th>
                    <th>@lang('validation.attributes.command_name')</th>
                    <th>@lang('validation.attributes.number_of_new_entries')</th>
                    <th>@lang('validation.attributes.duration_in_seconds')</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($updateLogs as $updateLog)
                    <tr>
                        <td>{{ $updateLog->created_at->format(__('common.date_formats.datetime')) }}</td>
                        <td>{{ formatBoolean($updateLog->is_successful) }}</td>
                        <td>{{ $updateLog->command_name }}</td>
                        <td>{{ $updateLog->number_of_new_entries }}</td>
                        <td>{{ $updateLog->duration_in_seconds }}</td>
                        <td>{{ Html::linkRoute('update_logs.show', __('update_log.index.details'), [$updateLog]) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $updateLogs->links() }}
    </div>
@endsection
