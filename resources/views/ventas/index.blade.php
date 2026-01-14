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
                    <div class="mt-4 text-4xl font-black text-slate-900">{{ (int)$cantidadVentas }}</div>
                    <div class="mt-2 text-sm text-slate-500">Conteo total según los filtros aplicados.</div>
                </div>
                <div class="relative overflow-hidden rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-50 via-white to-white p-6 shadow-sm">
                    <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-emerald-200/40 blur-xl"></div>
                    <div class="flex items-center justify-between">
                        <div class="inline-flex items-center gap-2 rounded-full bg-emerald-600/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-emerald-700">
                            Total en dinero
                        </div>
                        <span class="text-xs font-semibold text-emerald-600">Acumulado</span>
                    </div>
                    <div class="mt-4 text-4xl font-black text-slate-900">
                        $ {{ number_format((float)$totalDinero, 2, ',', '.') }}
                    </div>
                    <div class="mt-2 text-sm text-slate-500">Suma total del período filtrado.</div>
                </div>
                <div class="relative overflow-hidden rounded-3xl border border-violet-100 bg-gradient-to-br from-violet-50 via-white to-white p-6 shadow-sm">
                    <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-violet-200/40 blur-xl"></div>
                    <div class="flex items-center justify-between">
                        <div class="inline-flex items-center gap-2 rounded-full bg-violet-600/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-violet-700">
                            Ticket promedio
                        </div>
                        <span class="text-xs font-semibold text-violet-600">Promedio</span>
                    </div>
                    <div class="mt-4 text-4xl font-black text-slate-900">
                        $ {{ number_format((float)$ticketPromedio, 2, ',', '.') }}
                    </div>
                    <div class="mt-2 text-sm text-slate-500">Monto promedio por venta en el período.</div>
                </div>
            </div>

            {{-- Filtros (modernos + plegables) --}}
            <div x-data="{ open: {{ $desde || $hasta || $buscar ? 'true' : 'false' }} }"
                 class="rounded-3xl border border-slate-100 bg-gradient-to-br from-slate-50 via-white to-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-5">
                    <div>
                        <div class="text-base font-semibold text-slate-900">Filtro avanzado de ventas</div>
                        <div class="text-xs text-slate-500">Definí fechas o buscá por ID, método, estado o usuario.</div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button"
                                @click="open = !open"
                                class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white shadow hover:bg-black">
                            <span x-text="open ? 'Ocultar filtros' : 'Mostrar filtros'"></span>
                        </button>
                        <a href="{{ route('ventas.index') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-700 hover:bg-slate-100">
                            Limpiar
                        </a>
                    </div>
                </div>
                <div x-show="open" x-transition class="border-t border-slate-100 px-6 pb-6">
                    <form method="GET" action="{{ route('ventas.index') }}"
                          class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-12 items-end">
                        <div class="md:col-span-3">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Desde</label>
                            <input type="date" name="desde" value="{{ $desde }}"
                                   class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-slate-400 focus:ring-slate-200" />
                        </div>

                        <div class="md:col-span-3">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Hasta</label>
                            <input type="date" name="hasta" value="{{ $hasta }}"
                                   class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-slate-400 focus:ring-slate-200" />
                        </div>

                        <div class="md:col-span-4">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Buscar</label>
                            <input name="q" value="{{ $buscar }}"
                                   placeholder="ID, método, estado o usuario..."
                                   class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-2 text-sm shadow-sm focus:border-slate-400 focus:ring-slate-200" />
                        </div>

                        <div class="md:col-span-2 flex flex-col gap-2">
                            <button class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-blue-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:-translate-y-0.5 hover:from-blue-700 hover:to-blue-600">
                                Aplicar filtros
                            </button>
                            <a href="{{ route('ventas.index') }}"
                               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-100">
                                Restablecer
                            </a>
                        </div>
                    </form>
                    <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                        <span class="rounded-full bg-slate-100 px-3 py-1">Filtros activos: {{ $desde || $hasta || $buscar ? 'Sí' : 'No' }}</span>
                        <span>Aplicá filtros para refinar el total y el ticket promedio.</span>
                    </div>
                </div>
            </div>

            {{-- Tabla (ancho completo) --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
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
