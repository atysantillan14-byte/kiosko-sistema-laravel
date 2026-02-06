<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Productos</h2>
                <p class="app-subtitle">Administrá el inventario y aplicá filtros inteligentes.</p>
            </div>

            <a href="{{ route('productos.create') }}" class="app-btn-primary">
                <i class="fas fa-plus"></i>
                Nuevo producto
            </a>
        </div>
    </x-slot>

    <div class="app-page space-y-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-blue-50 text-blue-700">Total de productos</span>
                    <span class="text-xs font-semibold text-blue-600">Inventario</span>
                </div>
                <div class="mt-6 text-5xl font-semibold text-slate-900 sm:text-6xl">
                    {{ (int) $totalProductos }}
                </div>
                <p class="mt-2 text-sm text-slate-500">Productos registrados según los filtros activos.</p>
            </div>

            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-emerald-50 text-emerald-700">Más vendidos</span>
                    <span class="text-xs font-semibold text-emerald-600">Top 5</span>
                </div>
                <div class="mt-4 space-y-2 text-sm text-slate-600">
                    @forelse($topProductosVendidos as $producto)
                        <div class="flex items-center justify-between">
                            <span class="truncate text-slate-800">{{ $producto->producto_nombre ?? 'Producto eliminado' }}</span>
                            <span class="app-chip bg-emerald-50 text-emerald-700">
                                {{ (int) $producto->total_vendido }}
                            </span>
                        </div>
                    @empty
                        <span class="text-slate-400">Todavía no hay ventas registradas.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div x-data="{ open: {{ $q || $categoriaId ? 'true' : 'false' }} }" class="app-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Filtros de productos</h3>
                    <p class="text-xs text-slate-500">Buscá por nombre o SKU y filtrá por categoría.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="app-chip bg-slate-100 text-slate-600">
                        <i class="fas fa-filter mr-2"></i>
                        Filtros activos: {{ $q || $categoriaId ? 'Sí' : 'No' }}
                    </span>
                    <button type="button" @click="open = !open" class="app-btn-ghost border border-slate-200">
                        <i class="fas fa-sliders"></i>
                        <span x-text="open ? 'Ocultar filtros' : 'Mostrar filtros'"></span>
                    </button>
                </div>
            </div>

            <form x-show="open" x-transition class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3" method="GET" action="{{ route('productos.index') }}">
                <label class="text-xs font-semibold text-slate-500">
                    Buscar
                    <input class="app-input mt-1" name="q" value="{{ $q ?? '' }}" placeholder="Nombre o SKU...">
                </label>

                <label class="text-xs font-semibold text-slate-500">
                    Categoría
                    <select class="app-input mt-1" name="categoria_id">
                        <option value="">Todas</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id }}" @selected((string)$categoriaId === (string)$c->id)>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <div class="flex items-end gap-2">
                    <button class="app-btn-primary w-full" type="submit">
                        <i class="fas fa-magnifying-glass"></i>
                        Aplicar
                    </button>
                    <a href="{{ route('productos.index') }}" class="app-btn-secondary w-full">
                        <i class="fas fa-rotate-left"></i>
                        Limpiar
                    </a>
                </div>
            </form>

            <div class="mt-3 text-xs text-slate-500" x-show="open" x-transition>
                Tip: hacé click en una categoría en “Categorías” para ver sus productos automáticamente.
            </div>
        </div>

        <div class="app-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>SKU</th>
                            <th class="text-right">Precio</th>
                            <th class="text-right">Stock</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70">
                        @forelse($productos as $p)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $p->nombre }}</td>
                                <td>{{ $p->categoria?->nombre ?? 'Sin categoría' }}</td>
                                <td>{{ $p->sku ?? '-' }}</td>
                                <td class="text-right font-semibold text-slate-900">
                                    $ {{ number_format((float)$p->precio, 2, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    <span class="app-chip bg-emerald-50 text-emerald-700">
                                        {{ number_format((float) $p->stock, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('productos.edit', $p) }}" class="app-btn-secondary px-3 py-1.5 text-xs">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('productos.destroy', $p) }}" onsubmit="return confirm('¿Eliminar este producto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="app-btn-danger px-3 py-1.5 text-xs">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-sm text-slate-500" colspan="6">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-box-open text-2xl text-slate-300"></i>
                                        No hay productos para mostrar.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200/70 p-4">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
