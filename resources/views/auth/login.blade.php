@extends('layouts.app')

@section('title', __('login.title'))

@section('content')
    @include('shared._login')
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#email').focus();
        });
    </script>
@endsection