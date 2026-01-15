<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Panel principal</h2>
                <p class="app-subtitle">Resumen ejecutivo para operar el kiosko con velocidad y claridad.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('ventas.create') }}" class="app-btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    Registrar venta
                </a>
                <a href="{{ route('productos.create') }}" class="app-btn-secondary">
                    <i class="fas fa-box-open"></i>
                    Cargar producto
                </a>
            </div>
        </div>
    </x-slot>

    <div class="app-page space-y-8">
        <section class="relative overflow-hidden rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 p-8 text-white shadow-2xl">
            <div class="absolute -right-24 top-6 h-48 w-48 rounded-full bg-blue-500/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 h-40 w-40 rounded-full bg-indigo-500/20 blur-3xl"></div>
            <div class="relative">
                <span class="app-chip bg-white/10 text-xs font-semibold text-white">
                    <i class="fas fa-sparkles mr-2"></i>
                    SaaS premium ready
                </span>
                <div class="mt-5 grid gap-6 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)] lg:items-center">
                    <div>
                        <h3 class="text-3xl font-semibold leading-tight sm:text-4xl">Operá todo tu kiosko desde un solo panel moderno.</h3>
                        <p class="mt-3 text-sm text-slate-200">Accesos rápidos, información clara y flujos optimizados para vender más con menos esfuerzo.</p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('ventas.create') }}" class="app-btn-primary bg-white text-slate-900 hover:bg-slate-100">
                                <i class="fas fa-cash-register"></i>
                                Nueva venta
                            </a>
                            <a href="{{ route('productos.index') }}" class="app-btn-ghost bg-white/10 text-white hover:bg-white/20">
                                <i class="fas fa-boxes-stacked"></i>
                                Ver inventario
                            </a>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs font-semibold text-slate-200">Ventas hoy</div>
                            <div class="mt-2 text-3xl font-semibold">—</div>
                            <div class="text-xs text-slate-300">Sin datos vinculados</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs font-semibold text-slate-200">Ingresos hoy</div>
                            <div class="mt-2 text-3xl font-semibold">—</div>
                            <div class="text-xs text-slate-300">Conectá métricas en ventas</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs font-semibold text-slate-200">Stock bajo</div>
                            <div class="mt-2 text-3xl font-semibold">—</div>
                            <div class="text-xs text-slate-300">Alertas automáticas</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-xs font-semibold text-slate-200">Ticket promedio</div>
                            <div class="mt-2 text-3xl font-semibold">—</div>
                            <div class="text-xs text-slate-300">Indicador listo</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-3">
            <div class="app-card p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Accesos rápidos</h3>
                        <p class="app-subtitle">Flujos esenciales para operar al instante.</p>
                    </div>
                    <span class="app-chip bg-blue-50 text-blue-700">Favoritos</span>
                </div>
                <div class="mt-5 space-y-3">
                    <a href="{{ route('ventas.create') }}" class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-slate-900">
                        Registrar una venta
                        <i class="fas fa-arrow-right text-xs text-slate-400"></i>
                    </a>
                    <a href="{{ route('productos.create') }}" class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-slate-900">
                        Cargar nuevo producto
                        <i class="fas fa-arrow-right text-xs text-slate-400"></i>
                    </a>
                    <a href="{{ route('proveedores.index') }}" class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-slate-900">
                        Gestionar proveedores
                        <i class="fas fa-arrow-right text-xs text-slate-400"></i>
                    </a>
                </div>
            </div>

            <div class="app-card p-6 lg:col-span-2">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Módulos principales</h3>
                        <p class="app-subtitle">Entradas directas a cada sección del sistema.</p>
                    </div>
                    <span class="app-chip bg-slate-100 text-slate-600">Todo en uno</span>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
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
            </div>
        </section>
    </div>
</x-app-layout>
