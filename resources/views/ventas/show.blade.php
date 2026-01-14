<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Venta #{{ $venta->id }}</h2>
                <p class="text-sm text-slate-500">Detalle completo de la transacción.</p>
            </div>
            <x-button variant="outline" as="a" href="{{ route('ventas.index') }}">Volver</x-button>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <x-card>
                <p class="text-xs font-semibold text-slate-500">Usuario</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $venta->usuario?->name ?? '-' }}</p>
            </x-card>
            <x-card>
                <p class="text-xs font-semibold text-slate-500">Método</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">
                    @if($venta->metodo_pago === 'mixto')
                        Pago mixto
                    @else
                        {{ $venta->metodo_pago }}
                    @endif
                </p>
            </x-card>
            <x-card>
                <p class="text-xs font-semibold text-slate-500">Estado</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $venta->estado }}</p>
            </x-card>
            <x-card>
                <p class="text-xs font-semibold text-slate-500">Total</p>
                <p class="mt-2 text-2xl font-extrabold text-slate-900">
                    $ {{ number_format((float)$venta->total, 2, ',', '.') }}
                </p>
            </x-card>
        </div>

        @if($venta->metodo_pago === 'mixto' || $venta->metodo_pago_primario)
            <x-card title="Detalle de pago">
                <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-3">
                    <div>
                        <p class="text-xs font-semibold text-slate-500">Método 1</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ $venta->metodo_pago_primario ?? $venta->metodo_pago }}</p>
                        @if($venta->monto_primario)
                            <p class="text-slate-600">$ {{ number_format((float)$venta->monto_primario, 2, ',', '.') }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500">Método 2</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ $venta->metodo_pago_secundario ?? '—' }}</p>
                        @if($venta->monto_secundario)
                            <p class="text-slate-600">$ {{ number_format((float)$venta->monto_secundario, 2, ',', '.') }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500">Efectivo recibido</p>
                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $venta->efectivo_recibido ? '$ ' . number_format((float)$venta->efectivo_recibido, 2, ',', '.') : '—' }}
                        </p>
                        <p class="mt-2 text-xs text-slate-500">Vuelto</p>
                        <p class="font-semibold text-slate-900">
                            {{ $venta->efectivo_cambio ? '$ ' . number_format((float)$venta->efectivo_cambio, 2, ',', '.') : '—' }}
                        </p>
                    </div>
                </div>
            </x-card>
        @endif

        <x-table>
            <x-slot name="head">
                <tr>
                    <th class="px-6 py-4">Producto</th>
                    <th class="px-6 py-4">Cantidad</th>
                    <th class="px-6 py-4">Precio</th>
                    <th class="px-6 py-4">Subtotal</th>
                </tr>
            </x-slot>
            <x-slot name="body">
                @foreach($venta->detalles as $d)
                    <tr>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $d->producto?->nombre ?? 'Producto eliminado' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ (int)$d->cantidad }}</td>
                        <td class="px-6 py-4 text-slate-600">$ {{ number_format((float)$d->precio_unitario, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-900">$ {{ number_format((float)$d->subtotal, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>
    </div>
</x-app-layout>
