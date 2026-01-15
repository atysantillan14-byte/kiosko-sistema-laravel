<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">
                    Panel principal
                </h2>
                <p class="text-sm text-slate-500">Centro operativo del kiosko con accesos directos y gestión rápida.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto flex w-full max-w-6xl flex-col gap-8 px-4 sm:px-6 lg:px-8">
            <section class="rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 p-8 shadow-2xl">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[11px] font-semibold text-slate-200">
                            <i class="fas fa-sparkles"></i>
                            Panel de gestión
                        </span>
                        <h3 class="mt-4 text-3xl font-semibold text-white sm:text-4xl">
                            Bienvenido al centro operativo del kiosko
                        </h3>
                        <p class="mt-3 text-sm text-slate-300">
                            Accedé a las áreas clave del sistema con un diseño moderno, claro y profesional.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('ventas.create') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-2 text-sm font-semibold text-white transition hover:bg-white/20">
                            <i class="fas fa-plus-circle"></i>
                            Registrar venta
                        </a>
                        <a href="{{ route('productos.create') }}" class="inline-flex items-center gap-2 rounded-full border border-white/20 px-5 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                            <i class="fas fa-box-open"></i>
                            Cargar producto
                        </a>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <a href="{{ route('ventas.index') }}" class="group rounded-2xl border border-white/10 bg-white/5 p-5 text-white transition hover:-translate-y-1 hover:bg-white/10">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-rose-500/20 text-rose-200">
                                <i class="fas fa-receipt"></i>
                            </span>
                            <i class="fas fa-arrow-right text-xs text-white/60 transition group-hover:translate-x-1"></i>
                        </div>
                        <h4 class="mt-4 text-base font-semibold">Ventas</h4>
                        <p class="mt-1 text-xs text-slate-300">Registrar operaciones y ver historiales.</p>
                    </a>

                    <a href="{{ route('productos.index') }}" class="group rounded-2xl border border-white/10 bg-white/5 p-5 text-white transition hover:-translate-y-1 hover:bg-white/10">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-200">
                                <i class="fas fa-boxes-stacked"></i>
                            </span>
                            <i class="fas fa-arrow-right text-xs text-white/60 transition group-hover:translate-x-1"></i>
                        </div>
                        <h4 class="mt-4 text-base font-semibold">Productos</h4>
                        <p class="mt-1 text-xs text-slate-300">Inventario, stock y precios.</p>
                    </a>

                    <a href="{{ route('categorias.index') }}" class="group rounded-2xl border border-white/10 bg-white/5 p-5 text-white transition hover:-translate-y-1 hover:bg-white/10">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-amber-500/20 text-amber-200">
                                <i class="fas fa-tags"></i>
                            </span>
                            <i class="fas fa-arrow-right text-xs text-white/60 transition group-hover:translate-x-1"></i>
                        </div>
                        <h4 class="mt-4 text-base font-semibold">Categorías</h4>
                        <p class="mt-1 text-xs text-slate-300">Ordená tu catálogo por familias.</p>
                    </a>

                    <a href="{{ route('proveedores.index') }}" class="group rounded-2xl border border-white/10 bg-white/5 p-5 text-white transition hover:-translate-y-1 hover:bg-white/10">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-violet-500/20 text-violet-200">
                                <i class="fas fa-truck"></i>
                            </span>
                            <i class="fas fa-arrow-right text-xs text-white/60 transition group-hover:translate-x-1"></i>
                        </div>
                        <h4 class="mt-4 text-base font-semibold">Proveedores</h4>
                        <p class="mt-1 text-xs text-slate-300">Abastecimiento y acuerdos clave.</p>
                    </a>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
