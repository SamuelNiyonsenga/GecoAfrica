<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CMS') · GECO Africa</title>
    <link rel="icon" href="{{ asset('images/geco-mark.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body admin-guest">@yield('content')</body>
</html>