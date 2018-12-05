@extends('layouts.app')

@section('title', __('home.show.title'))

@section('content')
    <h1>

    </h1>

    <div class="row">
        <div class="col-lg-6">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Höhe:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Letzter Wert:</td>
                        <td> µSv/h</td>
                    </tr>
                    <tr>
                        <td>Längengrad</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Breitengrad</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection