<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @hasSection('title')
        <title>{{ config('app.name') }}- @yield('title') </title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif

    <!-- Scripts -->
</head>
<body class="m-4 bg-gray-600">
@include('layouts.search')
@include('layouts.navbar')


<main>
    @yield('content')
</main>
</body>
</html>
