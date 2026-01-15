<x-app-layout
    :hide-nav="true"
    :full-bleed="true"
    body-class="app-professional"
    shell-class="min-h-screen bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-50 via-slate-100 to-slate-200"
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
    @endphp

    <section class="px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        <div class="mx-auto flex min-h-[calc(100vh-5rem)] max-w-6xl flex-col">
            <div class="rounded-[32px] border border-[rgb(var(--border))]/90 bg-[rgb(var(--surface))]/95 p-6 shadow-[0_30px_80px_-45px_rgba(15,23,42,0.45)] sm:p-10">
                <div class="rounded-2xl border border-[rgb(var(--border))]/80 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-white via-white to-slate-50 px-6 py-8 text-center sm:px-10">
                    <div class="mx-auto inline-flex items-center justify-center rounded-3xl border border-[rgb(var(--border))]/80 bg-white/95 px-6 py-4 shadow-[0_18px_50px_-30px_rgba(37,99,235,0.45)]">
                        <img src="{{ $logoUrl }}" alt="Logo del negocio" class="h-14 w-auto sm:h-16">
                    </div>
                    <p class="mt-6 text-sm font-semibold uppercase tracking-[0.26em] text-[rgb(var(--muted))]">Inicio</p>
                    <h1 class="mt-2 text-3xl font-semibold text-[rgb(var(--text))] sm:text-4xl">
                        Hola, {{ auth()->user()->name }}
                    </h1>
                    <p class="mt-2 text-sm text-[rgb(var(--muted))]">Acceso directo a las áreas principales de tu negocio.</p>
                </div>

                <div class="mt-8">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-semibold text-[rgb(var(--text))]">Módulos principales</h2>
                            <p class="mt-1 text-sm text-[rgb(var(--muted))]">Ingresá directamente a las secciones clave del sistema.</p>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                        <a href="{{ route('ventas.index') }}" class="group rounded-2xl border border-[rgb(var(--border))]/80 bg-white/90 p-5 transition duration-200 hover:-translate-y-1 hover:border-[rgb(var(--primary))]/70 hover:bg-white">
                            <div class="flex items-center justify-between">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-[rgb(var(--border))]/70 bg-slate-50 text-[rgb(var(--text))]">
                                    <i class="fas fa-receipt"></i>
                                </span>
                                <i class="fas fa-arrow-right text-xs text-[rgb(var(--muted))] transition group-hover:translate-x-1"></i>
                            </div>
                            <h3 class="mt-4 text-sm font-semibold text-[rgb(var(--text))]">Ventas</h3>
                            <p class="mt-1 text-xs text-[rgb(var(--muted))]">Operaciones diarias.</p>
                        </a>
                        <a href="{{ route('productos.index') }}" class="group rounded-2xl border border-[rgb(var(--border))]/80 bg-white/90 p-5 transition duration-200 hover:-translate-y-1 hover:border-[rgb(var(--primary))]/70 hover:bg-white">
                            <div class="flex items-center justify-between">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-[rgb(var(--border))]/70 bg-slate-50 text-[rgb(var(--text))]">
                                    <i class="fas fa-boxes-stacked"></i>
                                </span>
                                <i class="fas fa-arrow-right text-xs text-[rgb(var(--muted))] transition group-hover:translate-x-1"></i>
                            </div>
                            <h3 class="mt-4 text-sm font-semibold text-[rgb(var(--text))]">Productos</h3>
                            <p class="mt-1 text-xs text-[rgb(var(--muted))]">Inventario y stock.</p>
                        </a>
                        <a href="{{ route('categorias.index') }}" class="group rounded-2xl border border-[rgb(var(--border))]/80 bg-white/90 p-5 transition duration-200 hover:-translate-y-1 hover:border-[rgb(var(--primary))]/70 hover:bg-white">
                            <div class="flex items-center justify-between">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-[rgb(var(--border))]/70 bg-slate-50 text-[rgb(var(--text))]">
                                    <i class="fas fa-tags"></i>
                                </span>
                                <i class="fas fa-arrow-right text-xs text-[rgb(var(--muted))] transition group-hover:translate-x-1"></i>
                            </div>
                            <h3 class="mt-4 text-sm font-semibold text-[rgb(var(--text))]">Categorías</h3>
                            <p class="mt-1 text-xs text-[rgb(var(--muted))]">Organización del catálogo.</p>
                        </a>
                        <a href="{{ route('dashboard') }}" class="group rounded-2xl border border-[rgb(var(--border))]/80 bg-white/90 p-5 transition duration-200 hover:-translate-y-1 hover:border-[rgb(var(--primary))]/70 hover:bg-white">
                            <div class="flex items-center justify-between">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-[rgb(var(--border))]/70 bg-slate-50 text-[rgb(var(--text))]">
                                    <i class="fas fa-chart-line"></i>
                                </span>
                                <i class="fas fa-arrow-right text-xs text-[rgb(var(--muted))] transition group-hover:translate-x-1"></i>
                            </div>
                            <h3 class="mt-4 text-sm font-semibold text-[rgb(var(--text))]">Estadísticas</h3>
                            <p class="mt-1 text-xs text-[rgb(var(--muted))]">Indicadores y métricas.</p>
                        </a>
                        <a href="{{ route('proveedores.index') }}" class="group rounded-2xl border border-[rgb(var(--border))]/80 bg-white/90 p-5 transition duration-200 hover:-translate-y-1 hover:border-[rgb(var(--primary))]/70 hover:bg-white">
                            <div class="flex items-center justify-between">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-[rgb(var(--border))]/70 bg-slate-50 text-[rgb(var(--text))]">
                                    <i class="fas fa-truck"></i>
                                </span>
                                <i class="fas fa-arrow-right text-xs text-[rgb(var(--muted))] transition group-hover:translate-x-1"></i>
                            </div>
                            <h3 class="mt-4 text-sm font-semibold text-[rgb(var(--text))]">Proveedores</h3>
                            <p class="mt-1 text-xs text-[rgb(var(--muted))]">Relaciones clave.</p>
                        </a>
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="app-btn-secondary border-[rgb(var(--border))] bg-white text-[rgb(var(--text))] hover:bg-slate-50">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
