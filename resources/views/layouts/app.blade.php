<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/additions.css') }}" rel="stylesheet">

    <script type="text/javascript">
        if (localStorage.getItem('theme') === 'dark' || window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.querySelector('html').classList.add('dark');
        }
    </script>
</head>
<body class="bg-gray-100 h-screen antialiased font-sans dark:bg-gray-900 dark:text-gray-300 transition-all transition-duration-1000 ease-in">
    <div id="app">
        <div class="mb-6 bg-blue-600 shadow">
            <div class="container lg:px-20 mx-auto">
                <div class="lg:flex lg:items-center">
                    <div class="flex justify-between items-center">
                        <div class="text-white text-xl mr-2 ml-2 md:ml-0 py-2"><a href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a></div>

                        <button class="lg:hidden py-4 px-6 text-xl transition-all duration-200 text-white text-opacity-50 hover:text-white hover:bg-pink-900 hover:bg-opacity-25" data-navbar-control>
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>

                    <div class="flex flex-grow flex-col lg:flex-row lg:justify-between transition-all duration-500 hidden overflow-hidden" data-navbar-content>
                        <div>
                            {!! \LaravelMenu::render() !!}
                        </div>

                        <div class="flex flex-col lg:flex-row">
                            {!! \LaravelMenu::render('user') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
