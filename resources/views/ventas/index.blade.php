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
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="text-sm font-semibold text-gray-600">Cantidad de ventas</div>
                    <div class="mt-2 text-4xl font-extrabold text-gray-900">{{ (int)$cantidadVentas }}</div>
                    <div class="text-xs text-gray-500 mt-1">Según filtros aplicados</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="text-sm font-semibold text-gray-600">Total en dinero</div>
                    <div class="mt-2 text-4xl font-extrabold text-gray-900">
                        $ {{ number_format((float)$totalDinero, 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Según filtros aplicados</div>
                </div>
            </div>

            {{-- Filtros (compactos) --}}
            <div class="bg-white rounded-xl shadow-sm p-5">
                <form method="GET" action="{{ route('ventas.index') }}"
                      class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-3">
                        <label class="text-sm font-semibold text-gray-700">Desde</label>
                        <input type="date" name="desde" value="{{ $desde }}"
                               class="mt-1 w-full rounded-lg border-gray-300" />
                    </div>

                    <div class="md:col-span-3">
                        <label class="text-sm font-semibold text-gray-700">Hasta</label>
                        <input type="date" name="hasta" value="{{ $hasta }}"
                               class="mt-1 w-full rounded-lg border-gray-300" />
                    </div>

                    <div class="md:col-span-4">
                        <label class="text-sm font-semibold text-gray-700">Buscar</label>
                        <input name="q" value="{{ $buscar }}"
                               placeholder="ID / método / estado / usuario..."
                               class="mt-1 w-full rounded-lg border-gray-300" />
                    </div>

                    <div class="md:col-span-2 flex gap-2">
                        <button class="w-full px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black">
                            Aplicar
                        </button>
                        <a href="{{ route('ventas.index') }}"
                           class="w-full px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-center">
                            Limpiar
                        </a>
                    </div>
                </form>
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
