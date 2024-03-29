<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Scripts -->
    @vite('resources/js/app.js')

    <script type="text/javascript">
        function onDomReady(callback) {
            const readyState = document.readyState;

            if (readyState === 'complete' || (readyState !== 'loading' && !document.documentElement.doScroll)) {
                callback();

                return;
            }

            document.addEventListener("DOMContentLoaded", callback);
        }
    </script>

    <!-- Styles -->
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 h-screen antialiased font-sans dark:bg-gray-900 dark:text-gray-300 transition-all transition-duration-1000 ease-in">
    <div class="min-h-screen bg-gray-100 dark:text-gray-400 dark:bg-gray-900">
        <div class="{{ classNames(['shadow' => !isset($header)]) }}">
            <x-navigation-menu/>
        </div>

        <!-- Page Heading -->
        @isset ($header)
            <header class="bg-white dark:bg-gray-700 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @include('flash::message')

                    @yield('breadcrumbs')

                    @yield('content')
                </div>
            </div>
        </main>
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

    @stack('scripts')
</body>
</html>
