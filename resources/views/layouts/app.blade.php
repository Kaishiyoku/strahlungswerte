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
    <script src="{{ asset('js/misc.js') }}"></script>

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
        <div class="bg-white shadow mb-6 dark:bg-gray-800">
            <div class="container lg:px-20 mx-auto">
                <div class="lg:flex lg:items-center">
                    <div class="flex justify-between items-center">
                        <div class="text-xl mr-2 ml-2 md:ml-0 py-5"><a href="{{ url('/') }}" class="text-gray-700 transition-all duration-200 hover:text-black dark:text-gray-400 dark:hover:text-white">{{ config('app.name', 'Laravel') }}</a></div>

                        <button class="lg:hidden py-4 px-6 text-xl transition-all outline-none duration-200 text-gray-500 hover:text-black hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-primary-200 focus:ring-inset dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" data-navbar-control>
                            <x-fas-bars class="w-5 h-5"/>
                        </button>
                    </div>

                    <div class="flex flex-grow flex-col items-center lg:flex-row lg:justify-between transition-all duration-500 overflow-hidden" data-navbar-content>
                        {!! \LaravelMenu::render() !!}

                        {!! \LaravelMenu::render('user') !!}
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
