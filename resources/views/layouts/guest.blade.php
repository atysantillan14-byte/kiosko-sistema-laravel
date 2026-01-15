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
    <body class="font-sans antialiased bg-[rgb(var(--bg))] text-[rgb(var(--text))]">
        <div class="min-h-screen bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-white via-slate-50 to-slate-100">
            <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">
                <div class="mb-8 flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-500 text-white shadow-sm">
                        <i class="fas fa-store text-lg"></i>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-slate-900">{{ config('app.name', 'Laravel') }}</div>
                        <div class="text-xs text-slate-500">Acceso seguro</div>
                    </div>
                </div>

                <div class="w-full max-w-md">
                    <div class="app-card p-6 shadow-lg">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
