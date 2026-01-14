<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-900">
<div class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-slate-100">
    @include('layouts.navigation')

    @isset($header)
        <header class="bg-white/80 backdrop-blur shadow-sm border-b border-slate-100">
            <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="w-full px-4 sm:px-6 lg:px-8 py-6">
        {{ $slot }}
    </main>
</div>
</body>
</html>
