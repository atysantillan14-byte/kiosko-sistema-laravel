<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Panel principal</h2>
                <p class="app-subtitle">Acceso directo a los módulos principales del sistema.</p>
            </div>
        </div>
    </x-slot>

    <div class="app-page">
        <section class="app-card p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Módulos principales</h3>
                    <p class="app-subtitle">Ingresá directamente a las secciones clave del sistema.</p>
                </div>
                <span class="app-chip bg-slate-100 text-slate-600">Todo en uno</span>
            </div>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                <a href="{{ route('ventas.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:border-blue-200">
                    <div class="flex items-center justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-50 text-rose-600">
                            <i class="fas fa-receipt"></i>
                        </span>
                        <i class="fas fa-arrow-right text-xs text-slate-400 transition group-hover:translate-x-1"></i>
                    </div>
                    <h4 class="mt-4 text-sm font-semibold text-slate-900">Ventas</h4>
                    <p class="mt-1 text-xs text-slate-500">Operaciones diarias.</p>
                </a>
                <a href="{{ route('productos.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:border-blue-200">
                    <div class="flex items-center justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                            <i class="fas fa-boxes-stacked"></i>
                        </span>
                        <i class="fas fa-arrow-right text-xs text-slate-400 transition group-hover:translate-x-1"></i>
                    </div>
                    <h4 class="mt-4 text-sm font-semibold text-slate-900">Productos</h4>
                    <p class="mt-1 text-xs text-slate-500">Inventario y stock.</p>
                </a>
                <a href="{{ route('categorias.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:border-blue-200">
                    <div class="flex items-center justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                            <i class="fas fa-tags"></i>
                        </span>
                        <i class="fas fa-arrow-right text-xs text-slate-400 transition group-hover:translate-x-1"></i>
                    </div>
                    <h4 class="mt-4 text-sm font-semibold text-slate-900">Categorías</h4>
                    <p class="mt-1 text-xs text-slate-500">Organización del catálogo.</p>
                </a>
                <a href="{{ route('dashboard') }}" class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:border-blue-200">
                    <div class="flex items-center justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <i class="fas fa-arrow-right text-xs text-slate-400 transition group-hover:translate-x-1"></i>
                    </div>
                    <h4 class="mt-4 text-sm font-semibold text-slate-900">Estadísticas</h4>
                    <p class="mt-1 text-xs text-slate-500">Indicadores y métricas.</p>
                </a>
                <a href="{{ route('proveedores.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:border-blue-200">
                    <div class="flex items-center justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-violet-50 text-violet-600">
                            <i class="fas fa-truck"></i>
                        </span>
                        <i class="fas fa-arrow-right text-xs text-slate-400 transition group-hover:translate-x-1"></i>
                    </div>
                    <h4 class="mt-4 text-sm font-semibold text-slate-900">Proveedores</h4>
                    <p class="mt-1 text-xs text-slate-500">Relaciones clave.</p>
                </a>
            </div>
        </section>
    </div>
</x-app-layout>
