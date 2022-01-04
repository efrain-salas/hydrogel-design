<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <title>{{ config('app.name') }}</title>

    @livewireStyles
</head>
<body class="bg-gray-200">
    {{ $slot }}

    @livewireScripts

    <script src="{{ secure_asset('js/konva.min.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ secure_asset('js/alpine.js') }}"></script>
</body>
</html>
