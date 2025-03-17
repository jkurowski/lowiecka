<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Dont index this page --}}
    <meta robots="noindex, nofollow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

    @stack('style')
</head>
<body>
@yield('content')

<!-- jQuery -->
<script src="{{ asset('/js/jquery.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/bootstrap.bundle.min.js') }}" charset="utf-8"></script>

<script src="{{ asset('/js/plan/imagemapster.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/plan/plan.js') }}" charset="utf-8"></script>

@stack('scripts')
</body>

</html>
