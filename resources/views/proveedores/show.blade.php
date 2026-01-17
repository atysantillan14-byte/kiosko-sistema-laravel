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
        $acciones = collect($proveedor->acciones ?? []);
        $accionesTimeline = $acciones
            ->map(function ($accion) {
                $fecha = $accion['fecha'] ?? null;
                $hora = $accion['hora'] ?? null;
                $timestamp = $fecha
                    ? \Illuminate\Support\Carbon::parse($fecha . ($hora ? ' ' . $hora : ' 00:00'))
                    : null;

                return [
                    'fecha' => $fecha,
                    'hora' => $hora,
                    'tipo' => $accion['tipo'] ?? 'Acción',
                    'productos' => $accion['productos'] ?? null,
                    'cantidad' => $accion['cantidad'] ?? null,
                    'monto' => $accion['monto'] ?? null,
                    'notas' => $accion['notas'] ?? null,
                    'timestamp' => $timestamp,
                    'es_creacion' => false,
                ];
            })
            ->push([
                'fecha' => $proveedor->created_at?->toDateString(),
                'hora' => $proveedor->created_at?->format('H:i'),
                'tipo' => 'Alta de proveedor',
                'productos' => null,
                'cantidad' => null,
                'monto' => null,
                'notas' => 'Proveedor creado en el sistema.',
                'timestamp' => $proveedor->created_at,
                'es_creacion' => true,
            ])
            ->sortBy(function ($accion) {
                return $accion['timestamp'] ?? \Illuminate\Support\Carbon::create(1970, 1, 1);
            })
            ->values();
    @endphp

    <div class="app-page space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                <div class="font-semibold">Revisá los errores antes de continuar</div>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
                        <p class="mt-2 text-sm text-slate-600">Registrá cada visita como una acción con productos y detalles.</p>
                    </div>
                    <a href="{{ route('proveedores.edit', $proveedor) }}" class="app-btn-secondary px-3 py-2 text-xs">Editar acciones</a>
                </div>
                <form class="mt-5 space-y-4 rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4" method="POST" action="{{ route('proveedores.acciones.store', $proveedor) }}">
                    @csrf
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                        <div>
                            <label class="app-label">Fecha</label>
                            <input type="date" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}" class="app-input">
                        </div>
                        <div>
                            <label class="app-label">Hora</label>
                            <input type="time" name="hora" value="{{ old('hora', now()->format('H:i')) }}" class="app-input">
                        </div>
                        <div>
                            <label class="app-label">Tipo</label>
                            <select name="tipo" class="app-input">
                                @foreach (['Pago', 'Productos', 'Visita', 'Nota', 'Otro'] as $tipo)
                                    <option value="{{ $tipo }}" @selected(old('tipo') === $tipo)>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="app-label">Monto</label>
                            <input type="number" name="monto" value="{{ old('monto') }}" class="app-input" min="0" step="0.01" placeholder="0.00">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                        <div class="md:col-span-2">
                            <label class="app-label">Productos</label>
                            <input name="productos" value="{{ old('productos') }}" class="app-input" placeholder="Ej: Papas, bebidas, golosinas">
                        </div>
                        <div>
                            <label class="app-label">Cantidad</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad') }}" class="app-input" min="0" placeholder="0">
                        </div>
                        <div>
                            <label class="app-label">Notas</label>
                            <input name="notas" value="{{ old('notas') }}" class="app-input" placeholder="Detalle de pago o entrega">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button class="app-btn-primary px-4 py-2 text-xs" type="submit">Agregar acción</button>
                    </div>
                </form>
                <div class="mt-5 space-y-3">
                    @forelse ($accionesTimeline as $accion)
                        @php
                            $fechaAccion = isset($accion['fecha']) && $accion['fecha']
                                ? \Illuminate\Support\Carbon::parse($accion['fecha'])->format('d/m/Y')
                                : 'Sin fecha';
                            $horaAccion = $accion['hora'] ?: 'Sin hora';
                            $montoTexto = $accion['monto'] !== null
                                ? '$' . number_format($accion['monto'], 2, ',', '.')
                                : null;
                        @endphp
                        <div class="rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    {{ $accion['tipo'] ?? 'Acción' }}
                                </div>
                                <span class="text-xs font-semibold text-slate-500">{{ $fechaAccion }} · {{ $horaAccion }}</span>
                            </div>
                            @if (!empty($accion['productos']))
                                <div class="mt-2 text-sm text-slate-900">
                                    <span class="font-semibold">Productos:</span>
                                    {{ $accion['productos'] }}
                                </div>
                            @endif
                            <div class="mt-1 text-xs text-slate-500">
                                Cantidad: {{ $accion['cantidad'] ?? 'Sin cantidad' }}
                                @if ($montoTexto)
                                    · Monto: {{ $montoTexto }}
                                @endif
                            </div>
                            @if (!empty($accion['notas']))
                                <div class="mt-2 text-xs text-slate-500">{{ $accion['notas'] }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 p-4 text-sm text-slate-500">
                            Aún no hay acciones registradas para este proveedor.
                        </div>
                    @endforelse
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
