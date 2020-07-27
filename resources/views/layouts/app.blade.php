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
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ URL::route('locations.index') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                {!! \LaravelMenu::render() !!}

                {!! \LaravelMenu::render('user') !!}
            </div>
        </div>
    </nav>

    <div class="container">
        @include('flash::message')

        @yield('breadcrumbs')

        @yield('content')
    </div>

    <div class="container mt-5 mb-3">
        @include('shared._footer')
    </div>
</div>

@auth
    <script type="text/javascript">
        const logoutAnchor = document.querySelector('a[href$="{{ url()->route('logout') }}"]');
        logoutAnchor.addEventListener('click', function (event) {
            event.preventDefault();
            document.querySelector('#logout-form').submit();
        });
    </script>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endauth

@yield('scripts')

</body>
</html>
