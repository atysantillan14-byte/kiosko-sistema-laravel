<x-app-layout
    :hideNav="true"
    :fullBleed="true"
    body-class="bg-slate-100 text-slate-900"
    shell-class="min-h-screen bg-gradient-to-br from-sky-50 via-blue-50 to-indigo-100 text-slate-900"
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
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.18),_transparent_55%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,_rgba(129,140,248,0.2),_transparent_60%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_rgba(255,255,255,0.7),_transparent_70%)]"></div>
        </div>

        <div class="relative app-container py-12 sm:py-16">
            <div class="mx-auto flex max-w-6xl flex-col gap-12">
                <div class="flex flex-col items-center gap-6 text-center">
                    <img src="{{ $logoUrl }}" alt="Logo del negocio" class="h-16 w-auto sm:h-20">

                    <div class="space-y-3">
                        <h1 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                            Hola, {{ auth()->user()->name }}
                        </h1>
                        <p class="text-base text-slate-600 sm:text-lg">
                            ¿Listo para vender hoy? Todo tu negocio a un clic de distancia.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-4">
                        <a
                            href="{{ $ventasRoute }}"
                            class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition hover:-translate-y-0.5 hover:bg-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/70"
                        >
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/20">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                                </svg>
                            </span>
                            Nueva venta
                        </a>
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/70 bg-white/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.32em] text-blue-700 shadow-sm">
                            Home rápida
                        </span>
                    </div>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    @foreach ($modules as $module)
                        <a
                            href="{{ $module['route'] }}"
                            class="group flex h-full flex-col justify-between gap-6 rounded-3xl border border-white/70 bg-white/70 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-400/60 hover:shadow-lg hover:shadow-blue-500/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/70"
                        >
                            <div class="flex items-center justify-between">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 transition group-hover:bg-blue-100">
                                    @switch($module['key'])
                                        @case('ventas')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8m-8 4h8m-6 4h6M6 3h9l3 3v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                                            </svg>
                                            @break
                                        @case('productos')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5-9 4.5L3 7.5z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5V17l9 4.5 9-4.5V7.5" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12v9.5" />
                                            </svg>
                                            @break
                                        @case('categorias')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h4l6 6-4 4-6-6V7z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7a2 2 0 1 0 0 4" />
                                            </svg>
                                            @break
                                        @case('estadisticas')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16V9" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V5" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 16v-6" />
                                            </svg>
                                            @break
                                        @case('proveedores')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v9" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 10h2.586a1 1 0 0 1 .707.293l2.414 2.414A1 1 0 0 1 22 13.414V16a2 2 0 0 1-2 2h-1" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 20a2 2 0 1 1 4 0" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20a2 2 0 1 1 4 0" />
                                            </svg>
                                            @break
                                    @endswitch
                                </span>
                                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-500">Ir</span>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">{{ $module['label'] }}</h3>
                                <p class="mt-2 text-sm text-slate-600">{{ $module['description'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="flex justify-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-white/80 px-6 py-3 text-sm font-semibold text-blue-700 shadow-sm transition hover:border-blue-300 hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/70"
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
