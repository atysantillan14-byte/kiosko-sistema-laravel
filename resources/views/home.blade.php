<x-app-layout
    :hideNav="true"
    :fullBleed="true"
    body-class="bg-slate-950 text-slate-100"
    shell-class="min-h-screen bg-gradient-to-br from-blue-900 via-indigo-900 to-slate-900 text-slate-100"
>
    @php
        $logoUrl = null;

        if (isset($business) && $business?->logo) {
            $logoUrl = \Illuminate\Support\Facades\Storage::url($business->logo);
        } elseif (isset($empresa) && $empresa?->logo) {
            $logoUrl = \Illuminate\Support\Facades\Storage::url($empresa->logo);
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists('logo.png')) {
            $logoUrl = \Illuminate\Support\Facades\Storage::url('logo.png');
        } else {
            $logoUrl = asset('img/logo.png');
        }

        $ventasRoute = \Illuminate\Support\Facades\Route::has('ventas.create')
            ? route('ventas.create')
            : route('ventas.index');

        $modules = [
            [
                'key' => 'ventas',
                'label' => 'Ventas',
                'description' => 'Operaciones diarias y cobranzas.',
                'route' => route('ventas.index'),
            ],
            [
                'key' => 'productos',
                'label' => 'Productos',
                'description' => 'Inventario, stock y precios.',
                'route' => route('productos.index'),
            ],
            [
                'key' => 'categorias',
                'label' => 'Categorías',
                'description' => 'Catálogo ordenado por familias.',
                'route' => route('categorias.index'),
            ],
            [
                'key' => 'estadisticas',
                'label' => 'Estadísticas',
                'description' => 'Indicadores y rendimiento general.',
                'route' => route('dashboard'),
            ],
            [
                'key' => 'proveedores',
                'label' => 'Proveedores',
                'description' => 'Relaciones clave y abastecimiento.',
                'route' => route('proveedores.index'),
            ],
        ];
    @endphp

    <section class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.15),_transparent_60%)]"></div>

        <div class="relative app-container py-16 sm:py-20">
            <div class="mx-auto flex max-w-6xl flex-col gap-12">
                <div class="flex flex-col items-center gap-6 text-center">
                    <img src="{{ $logoUrl }}" alt="Logo del negocio" class="h-16 w-auto sm:h-20">

                    <div class="space-y-3">
                        <h1 class="text-3xl font-semibold tracking-tight text-slate-100 sm:text-4xl lg:text-5xl">
                            Hola, <span class="text-blue-400 font-semibold">{{ auth()->user()->name }}</span>
                        </h1>
                        <p class="text-base text-slate-300 sm:text-lg">
                            ¿Listo para vender hoy? Todo tu negocio a un clic de distancia.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-4">
                        <a
                            href="{{ $ventasRoute }}"
                            class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-3 text-sm font-semibold text-white shadow-[0_20px_60px_-20px_rgba(59,130,246,0.8)] transition hover:-translate-y-0.5 hover:from-blue-500 hover:to-blue-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400/70"
                        >
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/20">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                                </svg>
                            </span>
                            Nueva venta
                        </a>
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/40 px-4 py-2 text-xs font-semibold uppercase tracking-[0.32em] text-slate-100 transition hover:bg-blue-500/10">
                            Home rápida
                        </span>
                    </div>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    @foreach ($modules as $module)
                        <a
                            href="{{ $module['route'] }}"
                            class="group flex h-full flex-col gap-6 rounded-3xl border border-slate-700 bg-slate-800/70 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-400/80 hover:shadow-[0_20px_40px_-24px_rgba(59,130,246,0.7)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400/70"
                        >
                            <div class="flex items-center">
                                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-500/10 text-blue-400 transition group-hover:bg-blue-500/20">
                                    @switch($module['key'])
                                        @case('ventas')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8m-8 4h8m-6 4h6M6 3h9l3 3v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                                            </svg>
                                            @break
                                        @case('productos')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5-9 4.5L3 7.5z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5V17l9 4.5 9-4.5V7.5" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12v9.5" />
                                            </svg>
                                            @break
                                        @case('categorias')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h4l6 6-4 4-6-6V7z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7a2 2 0 1 0 0 4" />
                                            </svg>
                                            @break
                                        @case('estadisticas')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16V9" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V5" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 16v-6" />
                                            </svg>
                                            @break
                                        @case('proveedores')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v9" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 10h2.586a1 1 0 0 1 .707.293l2.414 2.414A1 1 0 0 1 22 13.414V16a2 2 0 0 1-2 2h-1" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 20a2 2 0 1 1 4 0" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20a2 2 0 1 1 4 0" />
                                            </svg>
                                            @break
                                    @endswitch
                                </span>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-white">{{ $module['label'] }}</h3>
                                <p class="mt-2 text-sm text-slate-400">{{ $module['description'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="flex justify-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-full border border-white/30 px-6 py-3 text-sm font-semibold text-slate-100 shadow-sm transition hover:border-blue-300 hover:bg-blue-500/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400/70"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 8l4 4m0 0-4 4m4-4H9" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5" />
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
