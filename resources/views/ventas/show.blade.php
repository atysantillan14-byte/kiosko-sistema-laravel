<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Venta #{{ $venta->id }}</h2>
                <p class="app-subtitle">Detalle completo de la operación.</p>
            </div>
            <a href="{{ route('ventas.index') }}" class="app-btn-secondary">Volver</a>
        </div>
    </x-slot>

    <div class="app-page space-y-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            <div class="app-card p-5">
                <div class="text-xs font-semibold text-slate-500">Usuario</div>
                <div class="text-sm font-semibold text-slate-900">{{ $venta->usuario?->name ?? '-' }}</div>
            </div>
            <div class="app-card p-5">
                <div class="text-xs font-semibold text-slate-500">Método</div>
                <div class="text-sm font-semibold text-slate-900">
                    @if($venta->metodo_pago === 'mixto')
                        Pago mixto
                    @else
                        {{ $venta->metodo_pago }}
                    @endif
                </div>
            </div>
            <div class="app-card p-5">
                <div class="text-xs font-semibold text-slate-500">Estado</div>
                <div class="text-sm font-semibold text-slate-900">{{ $venta->estado }}</div>
            </div>
            <div class="app-card p-5">
                <div class="text-xs font-semibold text-slate-500">Total</div>
                <div class="text-2xl font-semibold text-slate-900">
                    $ {{ number_format((float)$venta->total, 2, ',', '.') }}
                </div>
            </div>
        </div>

        @if($venta->metodo_pago === 'mixto' || $venta->metodo_pago_primario)
            <div class="app-card p-6">
                <h3 class="text-sm font-semibold text-slate-900">Detalle de pago</h3>
                <div class="mt-4 grid grid-cols-1 gap-4 text-sm md:grid-cols-3">
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Método 1</div>
                        <div class="font-semibold text-slate-900">{{ $venta->metodo_pago_primario ?? $venta->metodo_pago }}</div>
                        @if($venta->monto_primario)
                            <div class="text-slate-600">$ {{ number_format((float)$venta->monto_primario, 2, ',', '.') }}</div>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Método 2</div>
                        <div class="font-semibold text-slate-900">{{ $venta->metodo_pago_secundario ?? '—' }}</div>
                        @if($venta->monto_secundario)
                            <div class="text-slate-600">$ {{ number_format((float)$venta->monto_secundario, 2, ',', '.') }}</div>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Efectivo recibido</div>
                        <div class="font-semibold text-slate-900">
                            {{ $venta->efectivo_recibido ? '$ ' . number_format((float)$venta->efectivo_recibido, 2, ',', '.') : '—' }}
                        </div>
                        <div class="text-xs font-semibold text-slate-500">Vuelto</div>
                        <div class="font-semibold text-slate-900">
                            {{ $venta->efectivo_cambio ? '$ ' . number_format((float)$venta->efectivo_cambio, 2, ',', '.') : '—' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="app-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70">
                        @foreach($venta->detalles as $d)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $d->producto?->nombre ?? 'Producto eliminado' }}</td>
                                <td>{{ number_format((float) $d->cantidad, 2, ',', '.') }}</td>
                                <td>$ {{ number_format((float)$d->precio_unitario, 2, ',', '.') }}</td>
                                <td class="font-semibold text-slate-900">$ {{ number_format((float)$d->subtotal, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
