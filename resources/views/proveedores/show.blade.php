<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Proveedor: {{ $proveedor->nombre }}</h2>
                <p class="app-subtitle">Registrá acciones como pagos, entregas y visitas del proveedor.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('proveedores.index') }}" class="app-btn-secondary">Volver</a>
                <a href="{{ route('proveedores.edit', $proveedor) }}" class="app-btn-primary">Editar acciones</a>
            </div>
        </div>
    </x-slot>

    @php
        $acciones = collect($proveedor->acciones ?? []);
        $pagosBase = (float) ($proveedor->pago ?? 0);
        $deudaBase = (float) ($proveedor->deuda ?? 0);
        $fechaBase = $proveedor->created_at ? $proveedor->created_at->format('Y-m-d') : null;
        $horaBase = $proveedor->created_at ? $proveedor->created_at->format('H:i') : null;
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
                ];
            })
            ->sortBy(function ($accion) {
                return $accion['timestamp'] ?? \Illuminate\Support\Carbon::create(1970, 1, 1);
            })
            ->values();

        $accionesPagos = $accionesTimeline->where('tipo', 'Pago')->values();
        $accionesProductos = $accionesTimeline->where('tipo', 'Productos')->values();
        $accionesOtros = $accionesTimeline
            ->reject(function ($accion) {
                return in_array($accion['tipo'], ['Pago', 'Productos'], true);
            })
            ->values();

        $pagosAccionesTotal = $accionesPagos->sum(fn ($accion) => (float) ($accion['monto'] ?? 0));
        $productosTotal = $accionesProductos->sum(fn ($accion) => (float) ($accion['monto'] ?? 0));
        $pagosTotal = $pagosAccionesTotal + $pagosBase;
        $deudaActual = $deudaBase + ($productosTotal - $pagosAccionesTotal);

        $accionesPagosDisplay = $accionesPagos->values();
        if ($pagosBase > 0) {
            $accionesPagosDisplay->push([
                'fecha' => $fechaBase,
                'hora' => $horaBase,
                'tipo' => 'Pago inicial',
                'productos' => null,
                'cantidad' => null,
                'monto' => $pagosBase,
                'notas' => 'Pago registrado previamente.',
            ]);
        }

        $accionesOtrosDisplay = $accionesOtros->values();
        if ($deudaBase > 0) {
            $accionesOtrosDisplay->push([
                'fecha' => $fechaBase,
                'hora' => $horaBase,
                'tipo' => 'Deuda inicial',
                'productos' => null,
                'cantidad' => null,
                'monto' => $deudaBase,
                'notas' => 'Deuda registrada previamente.',
            ]);
        }
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
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="app-card p-6 lg:col-span-3">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Acciones registradas</h3>
                        <p class="mt-2 text-sm text-slate-600">Registrá cada visita como una acción con productos y detalles.</p>
                    </div>
                    <a href="{{ route('proveedores.edit', $proveedor) }}" class="app-btn-secondary px-3 py-2 text-xs">Editar acciones</a>
                </div>
                <div class="mt-4 rounded-2xl border border-slate-200/70 bg-white p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Resumen</div>
                    <div class="mt-2 grid gap-3 text-sm text-slate-700 md:grid-cols-3">
                        <div>
                            <div class="text-xs uppercase text-slate-400">Pagos realizados</div>
                            <div class="mt-1 font-semibold text-slate-900">${{ number_format($pagosTotal, 2, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="text-xs uppercase text-slate-400">Productos entregados</div>
                            <div class="mt-1 font-semibold text-slate-900">${{ number_format($productosTotal, 2, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="text-xs uppercase text-slate-400">
                                {{ $deudaActual >= 0 ? 'Deuda actual' : 'Saldo a favor' }}
                            </div>
                            <div class="mt-1 font-semibold {{ $deudaActual >= 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                ${{ number_format(abs($deudaActual), 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
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
                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                    <div class="space-y-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pagos</div>
                        @forelse ($accionesPagosDisplay as $accion)
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
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pago</div>
                                    <span class="text-xs font-semibold text-slate-500">{{ $fechaAccion }} · {{ $horaAccion }}</span>
                                </div>
                                <div class="mt-1 text-xs text-slate-500">
                                    @if ($montoTexto)
                                        Monto: {{ $montoTexto }}
                                    @else
                                        Monto: Sin monto
                                    @endif
                                </div>
                                @if (!empty($accion['notas']))
                                    <div class="mt-2 text-xs text-slate-500">{{ $accion['notas'] }}</div>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 p-4 text-sm text-slate-500">
                                Aún no hay pagos registrados para este proveedor.
                            </div>
                        @endforelse
                    </div>
                    <div class="space-y-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Productos</div>
                        @forelse ($accionesProductos as $accion)
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
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Productos</div>
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
                                Aún no hay entregas de productos registradas para este proveedor.
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="mt-6 space-y-3">
                    @forelse ($accionesOtrosDisplay as $accion)
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
                            Aún no hay acciones adicionales registradas para este proveedor.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
