<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body {
                font-family: 'Inter', 'Figtree', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;
                background: #f8fafc;
                color: #0f172a;
            }

            .asset-warning {
                margin: 16px;
                padding: 12px 16px;
                border: 1px solid #fca5a5;
                background: #fef2f2;
                color: #7f1d1d;
                border-radius: 10px;
                font-size: 14px;
                line-height: 1.4;
            }

            .asset-warning code {
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New',
                    monospace;
            }
        </style>
    @endif
</head>
@php
    $mainClass = ($fullBleed ?? false) ? 'app-page' : 'app-container app-page';
@endphp
<body class="font-sans antialiased bg-[rgb(var(--bg))] text-[rgb(var(--text))] {{ $bodyClass }}">
@if (!file_exists(public_path('build/manifest.json')) && !file_exists(public_path('hot')))
    <div class="asset-warning">
        No se encontraron los assets compilados. Ejecutá <code>npm run build</code> (producción) o
        <code>npm run dev</code> (desarrollo) para regenerar <code>public/build/manifest.json</code>.
    </div>
@endif
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
