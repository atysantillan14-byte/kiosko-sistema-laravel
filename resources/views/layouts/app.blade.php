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
@php
    $bodyClass = $bodyClass ?? '';
    $shellClass = $shellClass ?? 'min-h-screen bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-white via-slate-50 to-slate-100';
    $mainClass = ($fullBleed ?? false) ? 'app-page' : 'app-container app-page';
@endphp
<body class="font-sans antialiased bg-[rgb(var(--bg))] text-[rgb(var(--text))] {{ $bodyClass }}">
<div class="{{ $shellClass }}">
    @if(!($hideNav ?? false))
        @include('layouts.navigation')
    @endif

    @if(!($hideNav ?? false))
        @isset($header)
            <header class="border-b border-slate-200/70 bg-white/80 backdrop-blur">
                <div class="app-container py-6">
                    {{ $header }}
                </div>
            </header>
        @endisset
    @endif

    <main class="{{ $mainClass }}">
        {{ $slot }}
    </main>
</div>
</body>
</html>
