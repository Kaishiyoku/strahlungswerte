@extends('layouts.app')

@section('title', __('home.show.title'))

@section('content')
    <h1>
        {{ $measurementSite->getLocation()->getPostalCode() }} {{ $measurementSite->getLocation()->getPlace() }}
    </h1>

    <div class="row">
        <div class="col-lg-6">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Höhe:</td>
                        <td>{{ $measurementSite->getLocation()->getHeight() }}m</td>
                    </tr>
                    <tr>
                        <td>Letzter Wert:</td>
                        <td>{{ $measurementSite->getLocation()->getLastMeasuredOneHourValue() }} µSv/h</td>
                    </tr>
                    <tr>
                        <td>Längengrad</td>
                        <td>{{ $measurementSite->getLocation()->getLongitude() }}</td>
                    </tr>
                    <tr>
                        <td>Breitengrad</td>
                        <td>{{ $measurementSite->getLocation()->getLatitude() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection