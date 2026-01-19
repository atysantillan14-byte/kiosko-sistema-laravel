<x-app-layout :title="'Cierre #' . $cierre->id">
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Cierre #{{ $cierre->id }}</h2>
                <p class="app-subtitle">Detalle del cierre guardado.</p>
            </div>
            <div class="flex flex-wrap gap-2 print-hidden">
                <a href="{{ route('ventas.cierres.index') }}" class="app-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver a cierres
                </a>
                <button type="button" onclick="window.print()" class="app-btn-primary">
                    <i class="fas fa-print"></i>
                    Exportar / Imprimir
                </button>
            </div>
        </div>
    </x-slot>

    <div class="app-page space-y-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            <div class="app-card p-5">
                <div class="text-xs font-semibold text-slate-500">Fecha</div>
                <div class="text-sm font-semibold text-slate-900">
                    {{ $cierre->created_at?->format('d/m/Y H:i') ?? '—' }}
                </div>
            </div>
            <div class="app-card p-5">
                <div class="text-xs font-semibold text-slate-500">Turno</div>
                <div class="text-sm font-semibold text-slate-900">
                    {{ $cierre->turno ? ucfirst($cierre->turno) : 'Todos' }}
                </div>
            </div>
            <div class="app-card p-5">
                <div class="text-xs font-semibold text-slate-500">Rango</div>
                <div class="text-sm font-semibold text-slate-900">
                    {{ $cierre->desde?->format('d/m/Y') ?? 'Sin datos' }}
                    —
                    {{ $cierre->hasta?->format('d/m/Y') ?? 'Sin datos' }}
                </div>
                <div class="text-xs text-slate-500">
                    {{ $cierre->hora_desde ?? '00:00' }} - {{ $cierre->hora_hasta ?? '23:59' }}
                </div>
            </div>
            <div class="app-card p-5">
                <div class="text-xs font-semibold text-slate-500">Cajero</div>
                <div class="text-sm font-semibold text-slate-900">{{ $cierre->usuario?->name ?? '-' }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-emerald-50 text-emerald-700">Total neto</span>
                    <span class="text-xs font-semibold text-emerald-600">Venta</span>
                </div>
                <div class="mt-4 text-3xl font-semibold text-slate-900">
                    $ {{ number_format((float) $cierre->total_neto, 2, ',', '.') }}
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    Bruto: $ {{ number_format((float) $cierre->total_bruto, 2, ',', '.') }} ·
                    Descuentos: $ {{ number_format((float) $cierre->total_descuentos, 2, ',', '.') }}
                </p>
            </div>
            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-amber-50 text-amber-700">Ventas</span>
                    <span class="text-xs font-semibold text-amber-600">Cantidad</span>
                </div>
                <div class="mt-4 text-3xl font-semibold text-slate-900">{{ (int) $cierre->cantidad_ventas }}</div>
                <p class="mt-2 text-xs text-slate-500">Ticket promedio: $ {{ number_format((float) $cierre->ticket_promedio, 2, ',', '.') }}</p>
            </div>
            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-blue-50 text-blue-700">Efectivo esperado</span>
                    <span class="text-xs font-semibold text-blue-600">Total</span>
                </div>
                <div class="mt-4 text-3xl font-semibold text-slate-900">
                    $ {{ number_format((float) $cierre->efectivo_esperado, 2, ',', '.') }}
                </div>
                <p class="mt-2 text-xs text-slate-500">Efectivo contado: $ {{ number_format((float) $cierre->efectivo_contado, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            <div class="app-card overflow-hidden print-avoid-break xl:col-span-2">
                <div class="border-b border-slate-200/70 px-5 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Desglose por medio de pago</h3>
                    <p class="text-xs text-slate-500">Montos y cantidad de transacciones por medio.</p>
                </div>
                <div class="overflow-x-auto print-overflow-visible">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Medio de pago</th>
                                <th>Monto</th>
                                <th># Transacciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70">
                            @forelse($desglosePagos as $pago)
                                <tr>
                                    <td class="font-semibold text-slate-900">{{ ucfirst($pago['metodo'] ?? '') }}</td>
                                    <td>$ {{ number_format((float) ($pago['monto'] ?? 0), 2, ',', '.') }}</td>
                                    <td>{{ $pago['transacciones'] ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-6 text-center text-sm text-slate-500" colspan="3">
                                        Sin datos de medios de pago registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="app-card p-6">
                <h3 class="text-sm font-semibold text-slate-900">Detalle de efectivo</h3>
                <p class="mt-1 text-xs text-slate-500">Resumen de caja chica, ingresos y retiros.</p>
                <dl class="mt-4 space-y-3 text-sm text-slate-600">
                    <div class="flex items-center justify-between">
                        <dt>Efectivo de ventas</dt>
                        <dd class="font-semibold text-slate-900">$ {{ number_format((float) $cierre->efectivo_ventas, 2, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Fondo inicial</dt>
                        <dd class="font-semibold text-slate-900">$ {{ number_format((float) $cierre->fondo_inicial, 2, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Ingresos</dt>
                        <dd class="font-semibold text-slate-900">$ {{ number_format((float) $cierre->ingresos, 2, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Retiros</dt>
                        <dd class="font-semibold text-slate-900">$ {{ number_format((float) $cierre->retiros, 2, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Devoluciones</dt>
                        <dd class="font-semibold text-slate-900">$ {{ number_format((float) $cierre->devoluciones, 2, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-200/70 pt-3">
                        <dt class="font-semibold text-slate-900">Efectivo esperado</dt>
                        <dd class="font-semibold text-slate-900">$ {{ number_format((float) $cierre->efectivo_esperado, 2, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Efectivo contado</dt>
                        <dd class="font-semibold text-slate-900">$ {{ number_format((float) $cierre->efectivo_contado, 2, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Diferencia</dt>
                        <dd class="font-semibold text-slate-900">$ {{ number_format((float) $cierre->diferencia, 2, ',', '.') }}</dd>
                    </div>
                </dl>
                @if($cierre->observaciones)
                    <div class="mt-4 rounded-xl bg-slate-50 p-3 text-xs text-slate-600">
                        <div class="font-semibold text-slate-700">Observaciones</div>
                        <p class="mt-1">{{ $cierre->observaciones }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="app-card overflow-hidden">
            <div class="border-b border-slate-200/70 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">Productos vendidos en el cierre</h3>
                <p class="text-xs text-slate-500">Detalle de los productos vendidos en este turno.</p>
            </div>
            <div class="overflow-x-auto print-overflow-visible">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-right">Cantidad</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70">
                        @forelse($productos as $producto)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $producto['producto'] ?? 'Producto sin nombre' }}</td>
                                <td class="text-right">{{ number_format((float) ($producto['cantidad'] ?? 0), 2, ',', '.') }}</td>
                                <td class="text-right">$ {{ number_format((float) ($producto['total'] ?? 0), 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-6 text-center text-sm text-slate-500" colspan="3">
                                    Sin productos registrados en el cierre.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($autoPrint)
        <script>
            window.addEventListener('load', () => {
                window.print();
            });
        </script>
    @endif
</x-app-layout>
