<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ventas
            </h2>

            <a href="{{ route('ventas.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                Nueva Venta
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Totales (más visibles) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-blue-100/60"></div>
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Cantidad de ventas</div>
                    <div class="mt-2 text-5xl font-black text-slate-900">{{ (int)$cantidadVentas }}</div>
                    <div class="text-xs text-slate-500 mt-2">Según filtros aplicados</div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-emerald-100/70"></div>
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total en dinero</div>
                    <div class="mt-2 text-5xl font-black text-slate-900">
                        $ {{ number_format((float)$totalDinero, 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-slate-500 mt-2">Según filtros aplicados</div>
                </div>
            </div>

            {{-- Filtros (modernos + plegables) --}}
            <div x-data="{ open: {{ $desde || $hasta || $buscar ? 'true' : 'false' }} }"
                 class="rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-4">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Filtros de ventas</div>
                        <div class="text-xs text-slate-500">Acotá el período o buscá por ID, método, estado o usuario.</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('ventas.index') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                            Limpiar
                        </a>
                        <button type="button"
                                @click="open = !open"
                                class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold text-white hover:bg-black">
                            <span x-text="open ? 'Ocultar filtros' : 'Mostrar filtros'"></span>
                        </button>
                    </div>
                </div>
                <div x-show="open" x-transition class="border-t border-slate-100 px-5 pb-5">
                    <form method="GET" action="{{ route('ventas.index') }}"
                          class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-12 items-end">
                        <div class="md:col-span-3">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Desde</label>
                            <input type="date" name="desde" value="{{ $desde }}"
                                   class="mt-2 w-full rounded-xl border-slate-200 bg-slate-50 focus:border-slate-400 focus:ring-slate-200" />
                        </div>

                        <div class="md:col-span-3">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Hasta</label>
                            <input type="date" name="hasta" value="{{ $hasta }}"
                                   class="mt-2 w-full rounded-xl border-slate-200 bg-slate-50 focus:border-slate-400 focus:ring-slate-200" />
                        </div>

                        <div class="md:col-span-4">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Buscar</label>
                            <input name="q" value="{{ $buscar }}"
                                   placeholder="ID / método / estado / usuario..."
                                   class="mt-2 w-full rounded-xl border-slate-200 bg-slate-50 focus:border-slate-400 focus:ring-slate-200" />
                        </div>

                        <div class="md:col-span-2 flex gap-2">
                            <button class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-black">
                                Aplicar
                            </button>
                            <a href="{{ route('ventas.index') }}"
                               class="w-full rounded-xl bg-slate-100 px-4 py-2 text-center text-sm font-semibold text-slate-600 hover:bg-slate-200">
                                Limpiar
                            </a>
                        </div>
                    </form>
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
                                           class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                                            Ver
                                        </a>
                                        <a href="{{ route('ventas.edit', $v) }}"
                                           class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
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
