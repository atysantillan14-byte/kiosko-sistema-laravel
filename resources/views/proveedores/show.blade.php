<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Proveedor: {{ $proveedor->nombre }}</h2>
                <p class="app-subtitle">Gestioná pagos, deudas, productos y próximas visitas del proveedor.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('proveedores.index') }}" class="app-btn-secondary">Volver</a>
                <a href="{{ route('proveedores.edit', $proveedor) }}" class="app-btn-primary">Editar acciones</a>
            </div>
        </div>
    </x-slot>

    @php
        $fechaVisita = $proveedor->proxima_visita ?: $proveedor->created_at;
        $fechaVisitaTexto = $fechaVisita ? $fechaVisita->format('d/m/Y') : 'Sin fecha';
        $horaTexto = $proveedor->hora ?: 'Sin hora';
        $pagoTexto = $proveedor->pago !== null ? '$' . number_format($proveedor->pago, 2, ',', '.') : 'Sin pago registrado';
        $deudaTexto = $proveedor->deuda !== null ? '$' . number_format($proveedor->deuda, 2, ',', '.') : 'Sin deuda pendiente';
    @endphp

    <div class="app-page space-y-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            <div class="app-card p-5">
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Estado</div>
                <div class="mt-3">
                    <span class="app-chip {{ $proveedor->activo ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $proveedor->activo ? 'activo' : 'inactivo' }}
                    </span>
                </div>
                <p class="mt-3 text-sm text-slate-600">Última actualización: {{ $proveedor->updated_at?->format('d/m/Y') ?? 'Sin datos' }}.</p>
            </div>
            <div class="app-card p-5">
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Próxima visita</div>
                <div class="mt-3 text-lg font-semibold text-slate-900">{{ $fechaVisitaTexto }}</div>
                <div class="mt-1 text-sm text-slate-500">{{ $horaTexto }}</div>
                <p class="mt-3 text-sm text-slate-600">Programá la próxima entrega cuando el proveedor confirme.</p>
            </div>
            <div class="app-card p-5">
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pagos</div>
                <div class="mt-3 text-lg font-semibold text-slate-900">{{ $pagoTexto }}</div>
                <div class="mt-1 text-sm text-slate-500">Deuda: {{ $deudaTexto }}</div>
                <p class="mt-3 text-sm text-slate-600">Actualizá pagos y saldos pendientes cuando ingresen.</p>
            </div>
            <div class="app-card p-5">
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Contacto</div>
                <div class="mt-3 text-sm font-semibold text-slate-900">{{ $proveedor->contacto ?: 'Sin responsable' }}</div>
                <div class="mt-1 text-sm text-slate-600">{{ $proveedor->telefono ?: 'Sin teléfono' }}</div>
                <div class="mt-1 text-sm text-slate-600">{{ $proveedor->email ?: 'Sin email' }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="app-card p-6 lg:col-span-2">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Acciones registradas</h3>
                        <p class="mt-2 text-sm text-slate-600">Gestioná pagos, deudas y próximas visitas desde un único lugar.</p>
                    </div>
                    <a href="{{ route('proveedores.edit', $proveedor) }}" class="app-btn-secondary px-3 py-2 text-xs">Editar acciones</a>
                </div>
                <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pago</div>
                        <div class="mt-2 text-base font-semibold text-slate-900">{{ $pagoTexto }}</div>
                        <p class="mt-1 text-xs text-slate-500">Registrá nuevos pagos y conciliaciones.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Deuda pendiente</div>
                        <div class="mt-2 text-base font-semibold text-slate-900">{{ $deudaTexto }}</div>
                        <p class="mt-1 text-xs text-slate-500">Marcá cuando se abone lo adeudado.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Próxima visita</div>
                        <div class="mt-2 text-base font-semibold text-slate-900">{{ $fechaVisitaTexto }}</div>
                        <p class="mt-1 text-xs text-slate-500">Hora estimada: {{ $horaTexto }}.</p>
                    </div>
                </div>
            </div>
            <div class="app-card p-6">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Condiciones</h3>
                <div class="mt-3 text-sm text-slate-700">{{ $proveedor->condiciones_pago ?: 'Sin condiciones registradas' }}</div>
                <div class="mt-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Dirección</div>
                    <div class="mt-2 text-sm text-slate-700">{{ $proveedor->direccion ?: 'Sin dirección' }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="app-card p-6 lg:col-span-2">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Productos entregados</h3>
                <div class="mt-4">
                    @if ($proveedor->productos_detalle)
                        <ul class="space-y-2 text-sm text-slate-700">
                            @foreach ($proveedor->productos_detalle as $detalle)
                                <li class="flex items-center justify-between rounded-xl border border-slate-200/70 bg-slate-50/70 px-4 py-2">
                                    <span class="font-semibold text-slate-900">{{ $detalle['nombre'] ?? 'Producto' }}</span>
                                    <span class="text-xs text-slate-500">{{ isset($detalle['cantidad']) ? $detalle['cantidad'] . ' unidades' : 'Sin cantidad' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="rounded-xl border border-dashed border-slate-200 p-4 text-sm text-slate-500">
                            {{ $proveedor->productos ?: 'Sin productos registrados' }}
                            <div class="mt-2 text-xs text-slate-500">
                                {{ $proveedor->cantidad !== null ? $proveedor->cantidad . ' unidades' : 'Sin cantidad' }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="app-card p-6">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Notas</h3>
                <p class="mt-3 text-sm text-slate-700">{{ $proveedor->notas ?: 'Sin notas adicionales.' }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
