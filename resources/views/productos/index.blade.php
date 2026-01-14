<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">
                    Productos
                </h2>
                <p class="text-sm text-slate-500">Administrá el inventario y aplicá filtros inteligentes.</p>
            </div>

            <a href="{{ route('productos.create') }}"
               class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:-translate-y-0.5 hover:bg-blue-700">
                Nuevo Producto
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Totales --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-white p-6 shadow-sm">
                    <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-blue-200/40 blur-xl"></div>
                    <div class="flex items-center justify-between">
                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-600/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-700">
                            Total de productos
                        </div>
                        <span class="text-xs font-semibold text-blue-600">Inventario</span>
                    </div>
                    <div class="mt-4 text-5xl font-extrabold tracking-tight text-slate-900">{{ (int) $totalProductos }}</div>
                    <div class="mt-2 text-sm text-slate-500">Productos registrados según los filtros activos.</div>
                </div>

                <div class="relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-white p-6 shadow-sm">
                    <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-blue-200/40 blur-xl"></div>
                    <div class="flex items-center justify-between">
                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-600/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-700">
                            Más vendidos
                        </div>
                        <span class="text-xs font-semibold text-blue-600">Top 5</span>
                    </div>
                    <div class="mt-4 space-y-2 text-sm text-slate-600">
                        @forelse($topProductosVendidos as $producto)
                            <div class="flex items-center justify-between">
                                <span class="truncate text-slate-800">{{ $producto->producto_nombre ?? 'Producto eliminado' }}</span>
                                <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-sm font-bold text-blue-700">
                                    {{ (int) $producto->total_vendido }}
                                </span>
                            </div>
                        @empty
                            <span class="text-slate-400">Todavía no hay ventas registradas.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Filtros estilo dashboard (plegables) --}}
            <div x-data="{ open: {{ $q || $categoriaId ? 'true' : 'false' }} }"
                 class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Filtros de productos</h3>
                        <p class="text-xs text-gray-500">Buscá por nombre o SKU y filtrá por categoría.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-600">
                            <i class="fas fa-filter mr-2"></i>
                            Filtros activos: {{ $q || $categoriaId ? 'Sí' : 'No' }}
                        </span>
                        <button type="button"
                                @click="open = !open"
                                class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-[11px] font-semibold text-slate-600 hover:bg-slate-50">
                            <i class="fas fa-sliders"></i>
                            <span x-text="open ? 'Ocultar filtros' : 'Mostrar filtros'">Mostrar filtros</span>
                        </button>
                    </div>
                </div>

                <form x-show="open"
                      x-transition
                      class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3"
                      method="GET"
                      action="{{ route('productos.index') }}">
                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500">
                        Buscar
                        <input
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            name="q"
                            value="{{ $q ?? '' }}"
                            placeholder="Nombre o SKU..."
                        >
                    </label>

                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500">
                        Categoría
                        <select
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            name="categoria_id"
                        >
                            <option value="">Todas</option>
                            @foreach($categorias as $c)
                                <option value="{{ $c->id }}" @selected((string)$categoriaId === (string)$c->id)>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <div class="flex items-end gap-2">
                        <button class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-800">
                            <i class="fas fa-magnifying-glass"></i>
                            Aplicar
                        </button>
                        <a href="{{ route('productos.index') }}"
                           class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            <i class="fas fa-rotate-left"></i>
                            Limpiar
                        </a>
                    </div>
                </form>

                <div class="mt-3 text-xs text-gray-500" x-show="open" x-transition>
                    Tip: hacé click en una categoría en “Categorías” para ver sus productos automáticamente.
                </div>
            </div>

            {{-- Tabla ancho completo --}}
            <div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-semibold text-gray-600">
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Categoría</th>
                            <th class="px-6 py-4">SKU</th>
                            <th class="px-6 py-4">Precio</th>
                            <th class="px-6 py-4">Stock</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                        @forelse($productos as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $p->nombre }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $p->categoria?->nombre ?? 'Sin categoría' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $p->sku ?? '-' }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    $ {{ number_format((float)$p->precio, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                                        {{ (int)$p->stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('productos.edit', $p) }}"
                                           class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('productos.destroy', $p) }}"
                                              onsubmit="return confirm('¿Eliminar este producto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-gray-600" colspan="6">
                                    No hay productos para mostrar.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $productos->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
