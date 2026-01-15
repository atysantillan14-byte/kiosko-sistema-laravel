<x-app-layout
    :hideNav="true"
    :fullBleed="true"
    body-class="bg-slate-950 text-slate-100"
    shell-class="min-h-screen bg-slate-950 text-slate-100"
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

        $modules = [
            [
                'label' => 'Ventas',
                'description' => 'Operaciones diarias y cobranzas.',
                'route' => route('ventas.index'),
                'icon' => 'fas fa-receipt',
            ],
            [
                'label' => 'Productos',
                'description' => 'Inventario, stock y precios.',
                'route' => route('productos.index'),
                'icon' => 'fas fa-boxes-stacked',
            ],
            [
                'label' => 'Categorías',
                'description' => 'Catálogo ordenado por familias.',
                'route' => route('categorias.index'),
                'icon' => 'fas fa-tags',
            ],
            [
                'label' => 'Estadísticas',
                'description' => 'Indicadores y rendimiento general.',
                'route' => route('dashboard'),
                'icon' => 'fas fa-chart-line',
            ],
            [
                'label' => 'Proveedores',
                'description' => 'Relaciones clave y abastecimiento.',
                'route' => route('proveedores.index'),
                'icon' => 'fas fa-truck',
            ],
        ];
    @endphp

    <section class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(99,102,241,0.18),_transparent_55%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,_rgba(37,99,235,0.16),_transparent_60%)]"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-950/95 to-slate-900"></div>
        </div>

        <div class="relative app-container py-12 lg:py-16">
            <div class="mx-auto flex max-w-6xl flex-col gap-10">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-[0_35px_80px_-45px_rgba(15,23,42,0.75)] backdrop-blur-lg sm:p-10">
                    <div class="flex flex-col items-center gap-6 text-center">
                        <div class="inline-flex items-center gap-4 rounded-2xl border border-white/15 bg-slate-900/60 px-5 py-4 shadow-[0_20px_50px_-30px_rgba(15,23,42,0.8)]">
                            <img src="{{ $logoUrl }}" alt="Logo del negocio" class="h-12 w-auto sm:h-14">
                            <div class="text-left">
                                <p class="text-xs font-semibold uppercase tracking-[0.32em] text-slate-400">Inicio</p>
                                <p class="text-sm text-slate-200">Panel principal</p>
                            </div>
                        </div>

                        <div class="max-w-2xl">
                            <h1 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl lg:text-5xl">
                                Hola, {{ auth()->user()->name }}
                            </h1>
                            <p class="mt-3 text-sm text-slate-300 sm:text-base">
                                Accedé a los módulos esenciales con una experiencia premium, clara y enfocada en productividad.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-[0_30px_70px_-50px_rgba(15,23,42,0.75)] backdrop-blur-lg sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-semibold tracking-tight text-white sm:text-2xl">Módulos principales</h2>
                            <p class="mt-2 text-sm text-slate-300">Entrá directo a cada sección clave del sistema.</p>
                        </div>
                        <span class="rounded-full border border-white/10 bg-slate-900/60 px-4 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">
                            Premium Ready
                        </span>
                    </div>

                    <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-5">
                        @foreach ($modules as $module)
                            <a
                                href="{{ $module['route'] }}"
                                class="group flex h-full flex-col gap-4 rounded-2xl border border-white/10 bg-slate-900/50 p-5 text-left transition duration-300 hover:-translate-y-1 hover:border-indigo-400/50 hover:bg-slate-900/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/80"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-slate-950 text-slate-100 transition duration-300 group-hover:border-indigo-400/60">
                                        <i class="{{ $module['icon'] }} text-sm"></i>
                                    </span>
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full border border-white/10 text-xs text-slate-400 transition duration-300 group-hover:border-indigo-400/60 group-hover:text-white">
                                        <i class="fas fa-arrow-up-right"></i>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-white">{{ $module['label'] }}</h3>
                                    <p class="mt-1 text-xs text-slate-400">{{ $module['description'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-6 py-3 text-sm font-semibold text-white transition hover:border-indigo-400/60 hover:bg-indigo-500/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/80"
                        >
                            <i class="fas fa-right-from-bracket text-xs"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
