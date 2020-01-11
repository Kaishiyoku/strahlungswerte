<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Laravel') }}
        -
        @yield('title')
    </title>

    {{ Html::style('css/app.css') }}

    {{ Html::script('js/app.js') }}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" charset="utf-8"></script>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary z-m-b-25">
        <div class="container">
            <a class="navbar-brand" href="{{ URL::route('locations.index') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                {!! Menu::render() !!}

                {!! Menu::render('user') !!}

                @auth
                    @include('shared._logout_form')
                @endauth
            </div>
        </div>
    </nav>

    <div class="container">
        @include('flash::message')

        @yield('breadcrumbs')

        @yield('content')
    </div>

    <div class="container">
        @include('shared._footer')
    </div>
</div>

@yield('scripts')

@mapstyles

@mapscripts

</body>
</html>
