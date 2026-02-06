<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Cierres de caja</h2>
                <p class="app-subtitle">Listado histórico de cierres guardados.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('ventas.cierre') }}" class="app-btn-secondary">
                    <i class="fas fa-cash-register"></i>
                    Nuevo cierre
                </a>
                <a href="{{ route('ventas.index') }}" class="app-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver a ventas
                </a>
            </div>
        </div>
    </x-slot>

    <div class="app-page space-y-6">
        <div class="app-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Rango</th>
                            <th>Turno</th>
                            <th>Total neto</th>
                            <th>Efectivo esperado</th>
                            <th>Usuario</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70">
                        @forelse($cierres as $cierre)
                            @php
                                $turnoLabel = $cierre->turno
                                    ? ([
                                        'manana' => 'Mañana',
                                        'tarde' => 'Tarde',
                                        'noche' => 'Noche',
                                    ][$cierre->turno] ?? ucfirst($cierre->turno))
                                    : 'Todos';
                            @endphp
                            <tr>
                                <td class="font-semibold text-slate-900">#{{ $cierre->id }}</td>
                                <td>{{ $cierre->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td>
                                    {{ $cierre->desde?->format('d/m/Y') ?? 'Sin datos' }}
                                    —
                                    {{ $cierre->hasta?->format('d/m/Y') ?? 'Sin datos' }}
                                    <div class="text-xs text-slate-500">
                                        {{ $cierre->hora_desde ?? '00:00' }} - {{ $cierre->hora_hasta ?? '23:59' }}
                                    </div>
                                </td>
                                <td>{{ $turnoLabel }}</td>
                                <td class="font-semibold text-slate-900">
                                    $ {{ number_format((float) $cierre->total_neto, 2, ',', '.') }}
                                </td>
                                <td>$ {{ number_format((float) $cierre->efectivo_esperado, 2, ',', '.') }}</td>
                                <td>{{ $cierre->usuario?->name ?? '-' }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('ventas.cierres.show', $cierre) }}" class="app-btn-secondary px-3 py-1.5 text-xs">
                                            Ver
                                        </a>
                                        <a href="{{ route('ventas.cierres.show', $cierre) }}?imprimir=1" class="app-btn-primary px-3 py-1.5 text-xs" target="_blank" rel="noopener noreferrer">
                                            <i class="fas fa-print"></i>
                                            Imprimir
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-sm text-slate-500" colspan="8">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-cash-register text-2xl text-slate-300"></i>
                                        No hay cierres guardados todavía.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200/70 p-4">
                {{ $cierres->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
