<nav x-data="{ open: false }" class="relative z-50 bg-white/90 backdrop-blur border-b border-slate-200/70 shadow-sm">
    @php
        $kioskoNombre = env('KIOSKO_NOMBRE', config('app.name'));
        $kioskoLogo = env('KIOSKO_LOGO', null);
    @endphp

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <!-- Brand (Logo + Nombre) -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        @if($kioskoLogo)
                            <img src="{{ $kioskoLogo }}" alt="Logo" class="h-9 w-9 rounded-xl object-cover shadow-sm ring-1 ring-slate-200/80">
                        @else
                            <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 shadow-sm ring-1 ring-slate-200/80"></div>
                        @endif

                        <div class="leading-tight">
                            <div class="text-sm font-semibold text-slate-900 group-hover:text-slate-950">{{ $kioskoNombre }}</div>
                            <div class="text-xs text-slate-500">Sistema de gestión</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex items-center gap-2 rounded-full bg-white/70 px-3 py-1 ring-1 ring-slate-200/70 shadow-sm">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
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
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 border border-slate-200 text-sm leading-4 font-medium rounded-full text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                            <div class="h-7 w-7 rounded-full bg-slate-900/10 flex items-center justify-center text-slate-700 font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden md:block">{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
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

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 focus:text-slate-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
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
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

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
