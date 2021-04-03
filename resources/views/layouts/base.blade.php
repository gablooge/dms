<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') | DMS BAPENDA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('styles')

</head>
<body>
    <div class="container mt-5">
        @yield('contents')
    </div>
    @yield('scripts')

</body>
</html>