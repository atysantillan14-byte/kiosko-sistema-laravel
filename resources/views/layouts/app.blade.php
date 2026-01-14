<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
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
<body
    class="font-sans text-slate-900 antialiased"
    x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', darkMode: localStorage.getItem('theme') === 'dark' }"
    x-init="document.documentElement.classList.toggle('dark', darkMode)"
>
<div class="flex min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <aside
        class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col border-r border-slate-200 bg-white px-4 py-6 transition-all duration-200 dark:border-slate-800 dark:bg-slate-900 lg:static"
        :class="{ '-translate-x-full lg:translate-x-0': !sidebarOpen, 'translate-x-0': sidebarOpen, 'lg:w-24': sidebarCollapsed }"
        x-cloak
    >
        <div class="flex items-center justify-between">
            @php
                $kioskoNombre = env('KIOSKO_NOMBRE', config('app.name'));
                $kioskoLogo = env('KIOSKO_LOGO', null);
            @endphp
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                @if($kioskoLogo)
                    <img src="{{ $kioskoLogo }}" alt="Logo" class="h-10 w-10 rounded-2xl object-cover shadow-soft">
                @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-soft">
                        <i class="fa-solid fa-store"></i>
                    </div>
                @endif
                <div class="leading-tight" :class="{ 'lg:hidden': sidebarCollapsed }">
                    <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $kioskoNombre }}</div>
                    <div class="text-xs text-slate-500">Sistema de gestión</div>
                </div>
            </a>
            <button type="button" class="hidden text-slate-500 hover:text-slate-700 lg:flex" @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)">
                <i class="fa-solid" :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
            </button>
        </div>

        <nav class="mt-8 space-y-2 text-sm">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2 font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <i class="fa-solid fa-chart-line"></i>
                <span :class="{ 'lg:hidden': sidebarCollapsed }">Dashboard</span>
            </a>
            <a href="{{ route('ventas.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2 font-semibold transition {{ request()->is('ventas*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <i class="fa-solid fa-receipt"></i>
                <span :class="{ 'lg:hidden': sidebarCollapsed }">Ventas</span>
            </a>
            <a href="{{ route('productos.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2 font-semibold transition {{ request()->is('productos*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <i class="fa-solid fa-box-open"></i>
                <span :class="{ 'lg:hidden': sidebarCollapsed }">Productos</span>
            </a>
            <a href="{{ route('categorias.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2 font-semibold transition {{ request()->is('categorias*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <i class="fa-solid fa-tags"></i>
                <span :class="{ 'lg:hidden': sidebarCollapsed }">Categorías</span>
            </a>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2 font-semibold transition {{ request()->routeIs('profile.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <i class="fa-solid fa-user-gear"></i>
                <span :class="{ 'lg:hidden': sidebarCollapsed }">Mi cuenta</span>
            </a>
        </nav>

        <div class="mt-auto space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-600 dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-300" :class="{ 'lg:hidden': sidebarCollapsed }">
            <div>
                <p class="text-[11px] uppercase tracking-wide text-slate-400">Workspace</p>
                <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-white">{{ $kioskoNombre }}</p>
                <p class="text-xs text-slate-500">Plan Profesional · 14 días restantes</p>
            </div>
            <x-button variant="outline" size="sm">Administrar plan</x-button>
        </div>
    </aside>

    <div class="flex flex-1 flex-col">
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/80 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80">
            <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" class="text-slate-500 hover:text-slate-700 lg:hidden" @click="sidebarOpen = true">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="space-y-1">
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Panel</h1>
                        @endisset
                        @php
                            $segments = request()->segments();
                            $path = '';
                        @endphp
                        <nav class="text-xs text-slate-500" aria-label="Breadcrumb">
                            <ol class="flex items-center gap-2">
                                <li><a href="{{ route('dashboard') }}" class="hover:text-brand-600">Inicio</a></li>
                                @foreach($segments as $segment)
                                    @php $path .= '/'.$segment; @endphp
                                    <li class="text-slate-300">/</li>
                                    <li class="capitalize">
                                        <a href="{{ url($path) }}" class="hover:text-brand-600">{{ str_replace('-', ' ', $segment) }}</a>
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden md:block">
                        <label class="relative block">
                            <span class="sr-only">Buscar</span>
                            <input type="text" placeholder="Buscar en el sistema..." class="w-64 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900">
                            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </label>
                    </div>

                    <button type="button" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 shadow-sm hover:border-brand-200 hover:text-brand-700 dark:border-slate-700 dark:bg-slate-900" @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', darkMode)">
                        <i class="fa-solid" :class="darkMode ? 'fa-moon' : 'fa-sun'"></i>
                    </button>

                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:border-brand-200 hover:text-brand-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-3 text-xs text-slate-500">
                                <p class="font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                            <div class="border-t border-slate-100"></div>
                            <x-dropdown-link :href="route('profile.edit')">
                                Perfil y seguridad
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Cerrar sesión
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <div class="container-shell py-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>

<x-toast />

<div
    x-show="sidebarOpen"
    x-transition.opacity
    class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"
    @click="sidebarOpen = false"
></div>
</body>
</html>
