<x-app-layout
    :hide-nav="true"
    :full-bleed="true"
    body-class="app-professional"
    shell-class="min-h-screen bg-[rgb(var(--bg))]"
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

    <section class="app-page">
        <div class="app-container">
            <div class="flex min-h-[calc(100vh-5rem)] flex-col py-12 lg:py-16">
                <div class="rounded-3xl border border-[rgb(var(--border))] bg-[rgb(var(--surface))]/95 p-6 shadow-[0_35px_90px_-55px_rgba(var(--text),0.35)] sm:p-10 lg:p-12">
                    <div class="rounded-3xl border border-[rgb(var(--border))] bg-[rgb(var(--surface))] px-6 py-10 text-center sm:px-12">
                        <div class="mx-auto inline-flex items-center justify-center rounded-3xl border border-[rgb(var(--border))] bg-[rgb(var(--surface))] px-6 py-4 shadow-[0_20px_60px_-35px_rgba(var(--text),0.25)]">
                            <img src="{{ $logoUrl }}" alt="Logo del negocio" class="h-16 w-auto sm:h-20">
                        </div>
                        <p class="mt-6 text-xs font-semibold uppercase tracking-[0.36em] text-[rgb(var(--muted))]">Inicio</p>
                        <h1 class="mt-3 text-3xl font-semibold text-[rgb(var(--text))] sm:text-4xl lg:text-5xl">
                            Hola, {{ auth()->user()->name }}
                        </h1>
                        <p class="mt-3 text-sm text-[rgb(var(--muted))] sm:text-base">Acceso directo a las áreas principales de tu negocio.</p>
                    </div>

                    <div class="mt-10">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-semibold text-[rgb(var(--text))] sm:text-2xl">Módulos principales</h2>
                                <p class="mt-2 text-sm text-[rgb(var(--muted))]">Ingresá directamente a las secciones clave del sistema.</p>
                            </div>
                        </div>

                        <div class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-5">
                            <a href="{{ route('ventas.index') }}" class="app-card group flex h-full flex-col rounded-2xl border border-[rgb(var(--border))] bg-[rgb(var(--surface))] p-5 shadow-[0_16px_45px_-40px_rgba(var(--text),0.35)] transition duration-300 hover:-translate-y-1 hover:border-[rgb(var(--primary))]">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-[rgb(var(--border))] bg-[rgb(var(--bg))] text-[rgb(var(--text))] transition duration-300 group-hover:border-[rgb(var(--primary))]">
                                        <i class="fas fa-receipt"></i>
                                    </span>
                                    <i class="fas fa-arrow-right text-xs text-[rgb(var(--muted))] transition duration-300 group-hover:translate-x-1 group-hover:text-[rgb(var(--text))]"></i>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-[rgb(var(--text))]">Ventas</h3>
                                <p class="mt-1 text-xs text-[rgb(var(--muted))]">Operaciones diarias.</p>
                            </a>
                            <a href="{{ route('productos.index') }}" class="app-card group flex h-full flex-col rounded-2xl border border-[rgb(var(--border))] bg-[rgb(var(--surface))] p-5 shadow-[0_16px_45px_-40px_rgba(var(--text),0.35)] transition duration-300 hover:-translate-y-1 hover:border-[rgb(var(--primary))]">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-[rgb(var(--border))] bg-[rgb(var(--bg))] text-[rgb(var(--text))] transition duration-300 group-hover:border-[rgb(var(--primary))]">
                                        <i class="fas fa-boxes-stacked"></i>
                                    </span>
                                    <i class="fas fa-arrow-right text-xs text-[rgb(var(--muted))] transition duration-300 group-hover:translate-x-1 group-hover:text-[rgb(var(--text))]"></i>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-[rgb(var(--text))]">Productos</h3>
                                <p class="mt-1 text-xs text-[rgb(var(--muted))]">Inventario y stock.</p>
                            </a>
                            <a href="{{ route('categorias.index') }}" class="app-card group flex h-full flex-col rounded-2xl border border-[rgb(var(--border))] bg-[rgb(var(--surface))] p-5 shadow-[0_16px_45px_-40px_rgba(var(--text),0.35)] transition duration-300 hover:-translate-y-1 hover:border-[rgb(var(--primary))]">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-[rgb(var(--border))] bg-[rgb(var(--bg))] text-[rgb(var(--text))] transition duration-300 group-hover:border-[rgb(var(--primary))]">
                                        <i class="fas fa-tags"></i>
                                    </span>
                                    <i class="fas fa-arrow-right text-xs text-[rgb(var(--muted))] transition duration-300 group-hover:translate-x-1 group-hover:text-[rgb(var(--text))]"></i>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-[rgb(var(--text))]">Categorías</h3>
                                <p class="mt-1 text-xs text-[rgb(var(--muted))]">Organización del catálogo.</p>
                            </a>
                            <a href="{{ route('dashboard') }}" class="app-card group flex h-full flex-col rounded-2xl border border-[rgb(var(--border))] bg-[rgb(var(--surface))] p-5 shadow-[0_16px_45px_-40px_rgba(var(--text),0.35)] transition duration-300 hover:-translate-y-1 hover:border-[rgb(var(--primary))]">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-[rgb(var(--border))] bg-[rgb(var(--bg))] text-[rgb(var(--text))] transition duration-300 group-hover:border-[rgb(var(--primary))]">
                                        <i class="fas fa-chart-line"></i>
                                    </span>
                                    <i class="fas fa-arrow-right text-xs text-[rgb(var(--muted))] transition duration-300 group-hover:translate-x-1 group-hover:text-[rgb(var(--text))]"></i>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-[rgb(var(--text))]">Estadísticas</h3>
                                <p class="mt-1 text-xs text-[rgb(var(--muted))]">Indicadores y métricas.</p>
                            </a>
                            <a href="{{ route('proveedores.index') }}" class="app-card group flex h-full flex-col rounded-2xl border border-[rgb(var(--border))] bg-[rgb(var(--surface))] p-5 shadow-[0_16px_45px_-40px_rgba(var(--text),0.35)] transition duration-300 hover:-translate-y-1 hover:border-[rgb(var(--primary))]">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-[rgb(var(--border))] bg-[rgb(var(--bg))] text-[rgb(var(--text))] transition duration-300 group-hover:border-[rgb(var(--primary))]">
                                        <i class="fas fa-truck"></i>
                                    </span>
                                    <i class="fas fa-arrow-right text-xs text-[rgb(var(--muted))] transition duration-300 group-hover:translate-x-1 group-hover:text-[rgb(var(--text))]"></i>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-[rgb(var(--text))]">Proveedores</h3>
                                <p class="mt-1 text-xs text-[rgb(var(--muted))]">Relaciones clave.</p>
                            </a>
                        </div>
                    </div>

                    <div class="mt-10 flex justify-center">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="app-btn-secondary">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
