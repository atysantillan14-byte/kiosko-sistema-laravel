<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/90 backdrop-blur">
    @php
        $kioskoNombre = env('KIOSKO_NOMBRE', config('app.name'));
        $kioskoLogo = env('KIOSKO_LOGO', null);
    @endphp

    <div class="app-container">
        <div class="flex h-16 items-center justify-between gap-6">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    @if($kioskoLogo)
                        <img src="{{ $kioskoLogo }}" alt="Logo de {{ $kioskoNombre }}" class="h-10 w-10 rounded-2xl object-cover shadow-sm ring-1 ring-slate-200/80">
                    @else
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-500 text-white shadow-sm">
                            <i class="fas fa-store text-lg"></i>
                        </div>
                    @endif
                    <div class="leading-tight">
                        <div class="text-sm font-semibold text-slate-900">{{ $kioskoNombre }}</div>
                    </div>
                </a>

                <div class="hidden items-center gap-1 rounded-full border border-slate-200/70 bg-slate-50/80 p-1 shadow-sm sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Inicio
                    </x-nav-link>

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Panel analítico
                    </x-nav-link>

                    <x-nav-link :href="url('/productos')" :active="request()->is('productos*')">
                        Productos
                    </x-nav-link>

                    <x-nav-link :href="url('/categorias')" :active="request()->is('categorias*')">
                        Categorías
                    </x-nav-link>

                    <x-nav-link :href="url('/ventas')" :active="request()->is('ventas*')">
                        Ventas
                    </x-nav-link>

                    <x-nav-link :href="route('proveedores.index')" :active="request()->is('proveedores*')">
                        Proveedores
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 rounded-full border border-slate-200/80 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600/10 text-sm font-bold text-blue-700">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden md:block">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Perfil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200/70 bg-white p-2 text-slate-600 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-200/70 bg-white sm:hidden">
        <div class="space-y-1 px-4 py-3">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                Inicio
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Panel analítico
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="url('/productos')" :active="request()->is('productos*')">
                Productos
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="url('/categorias')" :active="request()->is('categorias*')">
                Categorías
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="url('/ventas')" :active="request()->is('ventas*')">
                Ventas
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('proveedores.index')" :active="request()->is('proveedores*')">
                Proveedores
            </x-responsive-nav-link>
        </div>

        <div class="border-t border-slate-200/70 px-4 py-3">
            <div class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</div>
            <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Perfil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Cerrar sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
