<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Proveedores</h2>
                <p class="app-subtitle">Organizá contactos, acuerdos y abastecimiento del kiosko.</p>
            </div>
            <a class="app-btn-primary" href="{{ route('proveedores.create') }}">
                <i class="fas fa-user-plus"></i>
                Nuevo proveedor
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="app-card overflow-hidden">
            @if (session('success'))
                <div class="border-b border-emerald-200/70 bg-emerald-50 px-6 py-4 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="border-b border-rose-200/70 bg-rose-50 px-6 py-4 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif
            <div class="overflow-x-auto">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Proveedor</th>
                            <th>Contacto</th>
                            <th>Condiciones</th>
                            <th>Último pago</th>
                            <th>Próxima visita</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70">
                        @forelse($proveedores as $proveedor)
                            @php
                                $accionesTimeline = collect($proveedor->acciones ?? [])
                                    ->map(function ($accion) {
                                        $fecha = $accion['fecha'] ?? null;
                                        $hora = $accion['hora'] ?? null;
                                        $timestamp = $fecha
                                            ? \Illuminate\Support\Carbon::parse($fecha . ($hora ? ' ' . $hora : ' 00:00'))
                                            : null;

                                        return array_merge($accion, ['timestamp' => $timestamp]);
                                    })
                                    ->sortBy(function ($accion) {
                                        return $accion['timestamp'] ?? \Illuminate\Support\Carbon::create(1970, 1, 1);
                                    })
                                    ->values();
                                $pagosAcciones = $accionesTimeline
                                    ->filter(fn ($accion) => str_starts_with(strtolower($accion['tipo'] ?? ''), 'pago'))
                                    ->values();
                                $pagosBase = (float) ($proveedor->pago ?? 0);
                                $pagosDisplay = $pagosAcciones->map(fn ($accion) => [
                                    'timestamp' => $accion['timestamp'],
                                    'monto' => (float) ($accion['monto'] ?? 0),
                                ]);
                                if ($pagosBase > 0 && $proveedor->created_at) {
                                    $pagosDisplay = $pagosDisplay->push([
                                        'timestamp' => $proveedor->created_at,
                                        'monto' => $pagosBase,
                                    ]);
                                }
                                $ultimoPago = $pagosDisplay
                                    ->sortBy(fn ($pago) => $pago['timestamp'] ?? \Illuminate\Support\Carbon::create(1970, 1, 1))
                                    ->last();
                                $ultimoPagoMonto = $ultimoPago['monto'] ?? null;

                                $deudaBase = (float) ($proveedor->deuda ?? 0);
                                $deudasDisplay = $accionesTimeline
                                    ->filter(fn ($accion) => ($accion['deuda_pendiente'] ?? null) !== null)
                                    ->map(fn ($accion) => [
                                        'timestamp' => $accion['timestamp'],
                                        'monto' => (float) ($accion['deuda_pendiente'] ?? 0),
                                    ]);
                                if ($deudaBase > 0 && $proveedor->created_at) {
                                    $deudasDisplay = $deudasDisplay->push([
                                        'timestamp' => $proveedor->created_at,
                                        'monto' => $deudaBase,
                                    ]);
                                }
                                $ultimaDeuda = $deudasDisplay
                                    ->sortBy(fn ($deuda) => $deuda['timestamp'] ?? \Illuminate\Support\Carbon::create(1970, 1, 1))
                                    ->last();
                                $ultimaDeudaMonto = $ultimaDeuda['monto'] ?? null;
                            @endphp
                            <tr>
                                <td>
                                    <a class="font-semibold text-slate-900 transition hover:text-blue-600" href="{{ route('proveedores.show', $proveedor) }}">
                                        {{ $proveedor->nombre }}
                                    </a>
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $proveedor->email ?: 'Sin email' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm text-slate-700">{{ $proveedor->contacto ?: 'Sin responsable' }}</div>
                                    <div class="mt-1 text-xs text-slate-500">{{ $proveedor->telefono ?: 'Sin teléfono' }}</div>
                                </td>
                                <td>
                                    <div class="text-sm text-slate-700">{{ $proveedor->condiciones_pago ?: 'Sin condiciones registradas' }}</div>
                                    <div class="mt-1 text-xs text-slate-500">{{ $proveedor->direccion ?: 'Sin dirección' }}</div>
                                </td>
                                <td>
                                    <div class="text-sm text-slate-700">
                                        {{ $ultimoPagoMonto !== null && $ultimoPagoMonto > 0 ? '$' . number_format($ultimoPagoMonto, 2, ',', '.') : 'Sin pago' }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $ultimaDeudaMonto !== null && $ultimaDeudaMonto > 0 ? 'Debe $' . number_format($ultimaDeudaMonto, 2, ',', '.') : 'Sin deuda' }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $fechaVisita = $proveedor->proxima_visita ?: $proveedor->created_at;
                                    @endphp
                                    <div class="text-sm text-slate-700">
                                        {{ $fechaVisita ? $fechaVisita->format('d/m/Y') : 'Sin fecha' }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $proveedor->hora ?: 'Sin hora' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="app-chip {{ $proveedor->activo ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $proveedor->activo ? 'activo' : 'inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a class="app-btn-secondary px-3 py-1.5 text-xs" href="{{ route('proveedores.show', $proveedor) }}">
                                            Acciones
                                        </a>
                                        <a class="app-btn-secondary px-3 py-1.5 text-xs" href="{{ route('proveedores.edit', $proveedor) }}">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('proveedores.destroy', $proveedor) }}" onsubmit="return confirm('¿Eliminar este proveedor?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="app-btn-danger px-3 py-1.5 text-xs">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-sm text-slate-500" colspan="7">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-truck text-2xl text-slate-300"></i>
                                        Todavía no registraste proveedores.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
