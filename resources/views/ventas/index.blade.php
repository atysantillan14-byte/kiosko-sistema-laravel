<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">
                    Ventas
                </h2>
                <p class="text-sm text-slate-500">Indicadores clave y control de filtros para el período seleccionado.</p>
            </div>

            <a href="{{ route('ventas.create') }}"
               class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:-translate-y-0.5 hover:bg-blue-700">
                Nueva Venta
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Totales (más visibles) --}}
            @php
                $ticketPromedio = $cantidadVentas > 0 ? $totalDinero / $cantidadVentas : 0;
            @endphp
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-white p-6 shadow-sm">
                    <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-blue-200/40 blur-xl"></div>
                    <div class="flex items-center justify-between">
                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-600/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-700">
                            Cantidad de ventas
                        </div>
                        <span class="text-xs font-semibold text-blue-600">Total</span>
                    </div>
                    <div class="mt-4 text-5xl font-extrabold tracking-tight text-slate-900">{{ (int)$cantidadVentas }}</div>
                    <div class="mt-2 text-sm text-slate-500">Conteo total según los filtros aplicados.</div>
                </div>
                <div class="relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-white p-6 shadow-sm">
                    <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-blue-200/40 blur-xl"></div>
                    <div class="flex items-center justify-between">
                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-600/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-700">
                            Total en dinero
                        </div>
                        <span class="text-xs font-semibold text-blue-600">Acumulado</span>
                    </div>
                    <div class="mt-4 text-5xl font-extrabold tracking-tight text-slate-900">
                        $ {{ number_format((float)$totalDinero, 2, ',', '.') }}
                    </div>
                    <div class="mt-2 text-sm text-slate-500">Suma total del período filtrado.</div>
                </div>
                <div class="relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-white p-6 shadow-sm">
                    <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-blue-200/40 blur-xl"></div>
                    <div class="flex items-center justify-between">
                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-600/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-700">
                            Ticket promedio
                        </div>
                        <span class="text-xs font-semibold text-blue-600">Promedio</span>
                    </div>
                    <div class="mt-4 text-5xl font-extrabold tracking-tight text-slate-900">
                        $ {{ number_format((float)$ticketPromedio, 2, ',', '.') }}
                    </div>
                    <div class="mt-2 text-sm text-slate-500">Monto promedio por venta en el período.</div>
                </div>
            </div>

            {{-- Filtros (alineados al dashboard) --}}
            <div x-data="{ open: {{ $desde || $hasta || $buscar ? 'true' : 'false' }} }"
                 class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Filtros de ventas</h3>
                        <p class="text-xs text-gray-500">Definí fechas o buscá por ID, método, estado o usuario.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-600">
                            <i class="fas fa-filter mr-2"></i>
                            Filtros activos: {{ $desde || $hasta || $buscar ? 'Sí' : 'No' }}
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
                      class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-6"
                      method="GET"
                      action="{{ route('ventas.index') }}">
                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500">
                        Desde
                        <input
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            type="date"
                            name="desde"
                            value="{{ $desde }}"
                        >
                    </label>

                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500">
                        Hasta
                        <input
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            type="date"
                            name="hasta"
                            value="{{ $hasta }}"
                        >
                    </label>

                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500 md:col-span-2 xl:col-span-3">
                        Buscar
                        <input
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            name="q"
                            value="{{ $buscar }}"
                            placeholder="ID, método, estado o usuario..."
                        >
                    </label>

                    <div class="flex items-end gap-2 md:col-span-2 xl:col-span-1">
                        <button class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-800" type="submit">
                            <i class="fas fa-magnifying-glass-chart"></i>
                            Aplicar
                        </button>
                        <a href="{{ route('ventas.index') }}"
                           class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            <i class="fas fa-rotate-left"></i>
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabla (ancho completo) --}}
            <div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-semibold text-gray-600">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Fecha</th>
                            <th class="px-6 py-4">Usuario</th>
                            <th class="px-6 py-4">Método</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                        @forelse($ventas as $v)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-900">#{{ $v->id }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $v->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $v->usuario?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $v->metodo_pago }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                                        {{ $v->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    $ {{ number_format((float)$v->total, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('ventas.show', $v) }}"
                                           class="px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100">
                                            Ver
                                        </a>
                                        <a href="{{ route('ventas.edit', $v) }}"
                                           class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('ventas.destroy', $v) }}"
                                              onsubmit="return confirm('¿Eliminar esta venta?');">
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
                                <td class="px-6 py-8 text-gray-600" colspan="7">
                                    No hay ventas para mostrar.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $ventas->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
