<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
        <div class="relative flex min-h-screen items-center justify-center px-4 py-12">
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-brand-50 via-white to-slate-100"></div>
            <div class="relative w-full max-w-md">
                <div class="mb-8 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-soft">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <h1 class="mt-4 text-2xl font-semibold text-slate-900">{{ config('app.name', 'Kiosko SaaS') }}</h1>
                    <p class="mt-1 text-sm text-slate-500">Accedé a tu panel de gestión</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-soft backdrop-blur">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center text-xs text-slate-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Kiosko SaaS') }}. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </body>
</html>
