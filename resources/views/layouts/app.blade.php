<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/additions.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen antialiased font-sans">
    <div id="app">
        <header class="bg-blue-600 mb-8">
            <div class="container px-4 lg:px-20 mx-auto">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-xl text-gray-100 no-underline hover:no-underline hover:text-white pr-4">
                        {{ config('app.name', 'Laravel') }}
                    </a>

                    {!! \LaravelMenu::render() !!}

                    {!! \LaravelMenu::render('user') !!}
                </div>
            </div>
        </header>

        <div class="container px-4 lg:px-20 mx-auto">
            @include('flash::message')

            @yield('breadcrumbs')

            @yield('content')

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
