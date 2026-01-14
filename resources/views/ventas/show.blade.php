<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Venta #{{ $venta->id }}
            </h2>

            <a href="{{ route('ventas.index') }}"
               class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl shadow-sm p-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <div class="text-xs font-semibold text-gray-500">Usuario</div>
                    <div class="font-bold">{{ $venta->usuario?->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500">Método</div>
                    <div class="font-bold">{{ $venta->metodo_pago }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500">Estado</div>
                    <div class="font-bold">{{ $venta->estado }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-500">Total</div>
                    <div class="text-2xl font-extrabold">
                        $ {{ number_format((float)$venta->total, 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-semibold text-gray-600">
                            <th class="px-6 py-4">Producto</th>
                            <th class="px-6 py-4">Cantidad</th>
                            <th class="px-6 py-4">Precio</th>
                            <th class="px-6 py-4">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @foreach($venta->detalles as $d)
                            <tr>
                                <td class="px-6 py-4 font-semibold">{{ $d->producto?->nombre ?? 'Producto eliminado' }}</td>
                                <td class="px-6 py-4">{{ (int)$d->cantidad }}</td>
                                <td class="px-6 py-4">$ {{ number_format((float)$d->precio_unitario, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 font-bold">$ {{ number_format((float)$d->subtotal, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>


