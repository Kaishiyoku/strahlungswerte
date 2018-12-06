@extends('layouts.app')

@section('title', __('home.index.title'))

@section('content')
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Letzter Wert</th>
                <th>Höhe</th>
                <th>Längengrad</th>
                <th>Breitengrad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($locations as $location)
                <tr>
                    <td>{{ Html::linkRoute('home.show', $location->name, toSlug($location->uuid, $location->name)) }}</td>
                    <td>{{ $location->status->name }}</td>
                    <td>{{ $location->last_measured_one_hour_value }}</td>
                    <td>{{ $location->height }}</td>
                    <td>{{ $location->longitude }}</td>
                    <td>{{ $location->latitude }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $locations->links() }}
@endsection