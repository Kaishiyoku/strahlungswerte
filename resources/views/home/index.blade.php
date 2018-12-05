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
                    <td>{{ Html::linkRoute('home.show', $location->getPlace(), toSlug($location->getUuid(), $location->getPlace())) }}</td>
                    <td>{{ $location->getStatus() }}</td>
                    <td>{{ $location->getLastMeasuredOneHourValue() }}</td>
                    <td>{{ $location->getHeight() }}</td>
                    <td>{{ $location->getLongitude() }}</td>
                    <td>{{ $location->getLatitude() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection