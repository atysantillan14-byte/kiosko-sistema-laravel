<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Proveedor: {{ $proveedor->nombre }}</h2>
                <p class="app-subtitle">Registrá acciones como pagos, entregas y visitas del proveedor.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('proveedores.index') }}" class="app-btn-secondary">Volver</a>
                <a href="{{ route('proveedores.edit', $proveedor) }}" class="app-btn-primary">Editar proveedor</a>
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
                    'deuda_pendiente' => $accion['deuda_pendiente'] ?? null,
                    'notas' => $accion['notas'] ?? null,
                    'timestamp' => $timestamp,
                ];
            })
            ->sortBy(function ($accion) {
                return $accion['timestamp'] ?? \Illuminate\Support\Carbon::create(1970, 1, 1);
            })
            ->values();

        $accionDeudaReferencia = $accionesTimeline
            ->filter(fn ($accion) => $accion['deuda_pendiente'] !== null && $accion['timestamp'])
            ->last();
        $accionesCalculo = $accionDeudaReferencia && $accionDeudaReferencia['timestamp']
            ? $accionesTimeline->filter(function ($accion) use ($accionDeudaReferencia) {
                return $accion['timestamp']
                    && $accion['timestamp']->greaterThan($accionDeudaReferencia['timestamp']);
            })
            : $accionesTimeline;

        $accionesPagos = $accionesTimeline
            ->filter(fn ($accion) => str_starts_with(strtolower($accion['tipo'] ?? ''), 'pago'))
            ->values();
        $accionesProductos = $accionesTimeline
            ->filter(function ($accion) {
                $tipo = strtolower($accion['tipo'] ?? '');

                return str_contains($tipo, 'producto') && ! str_starts_with($tipo, 'pago');
            })
            ->values();
        $accionesProductosCalculo = $accionesCalculo
            ->filter(function ($accion) {
                $tipo = strtolower($accion['tipo'] ?? '');

                return str_contains($tipo, 'producto') && ! str_starts_with($tipo, 'pago');
            })
            ->values();
        $accionesOtros = $accionesTimeline
            ->reject(function ($accion) {
                $tipo = strtolower($accion['tipo'] ?? '');

                return $tipo === 'productos' || str_starts_with($tipo, 'pago');
            })
            ->values();

        $productosBaseDetalle = collect($proveedor->productos_detalle ?? []);
        $productosBaseCantidad = $productosBaseDetalle->sum(fn ($item) => (float) ($item['cantidad'] ?? 0));
        $productosBaseCantidad = $productosBaseCantidad > 0 ? $productosBaseCantidad : (float) ($proveedor->cantidad ?? 0);
        $productosBaseTexto = $productosBaseDetalle
            ->map(function ($item) {
                $nombre = $item['nombre'] ?? null;
                $cantidad = $item['cantidad'] ?? null;

                if ($nombre && $cantidad !== null) {
                    return sprintf('%s (%s)', $nombre, $cantidad);
                }

                return $nombre ?: null;
            })
            ->filter()
            ->implode(', ');
        $productosBaseTexto = $productosBaseTexto ?: ($proveedor->productos ?? null);

        $pagosAccionesTotal = $accionesPagos->sum(fn ($accion) => (float) ($accion['monto'] ?? 0));
        $productosCantidadTotal = $accionesProductos->sum(fn ($accion) => (float) ($accion['cantidad'] ?? 0)) + $productosBaseCantidad;
        $productosMontoTotal = $accionesProductosCalculo->sum(fn ($accion) => (float) ($accion['monto'] ?? 0));
        $pagosDeudaTotal = $accionesCalculo
            ->filter(function ($accion) {
                $tipo = strtolower($accion['tipo'] ?? '');

                return str_starts_with($tipo, 'pago') && ($tipo === 'pago' || str_contains($tipo, 'deuda'));
            })
            ->sum(fn ($accion) => (float) ($accion['monto'] ?? 0));
        $pagosProductosTotal = $accionesCalculo
            ->filter(function ($accion) {
                $tipo = strtolower($accion['tipo'] ?? '');

                return str_starts_with($tipo, 'pago') && str_contains($tipo, 'producto');
            })
            ->sum(fn ($accion) => (float) ($accion['monto'] ?? 0));
        $pagosTotal = $pagosAccionesTotal + $pagosBase;
        $deudaBaseCalculo = $accionDeudaReferencia ? (float) $accionDeudaReferencia['deuda_pendiente'] : $deudaBase;
        $deudaActual = $deudaBaseCalculo + ($productosMontoTotal - $pagosDeudaTotal - $pagosProductosTotal);
        $deudaActualDisplay = max($deudaActual, 0);

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

        $accionesProductosDisplay = $accionesProductos->values();
        if ($productosBaseCantidad > 0 || $productosBaseTexto) {
            $accionesProductosDisplay->push([
                'fecha' => $fechaBase,
                'hora' => $horaBase,
                'tipo' => 'Productos',
                'productos' => $productosBaseTexto,
                'cantidad' => $productosBaseCantidad ?: null,
                'monto' => null,
                'notas' => 'Productos registrados previamente.',
            ]);
        }

        $accionesDisplay = collect([$accionesPagosDisplay, $accionesProductosDisplay, $accionesOtrosDisplay])
            ->flatten(1)
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
                    <a href="{{ route('proveedores.edit', $proveedor) }}" class="app-btn-secondary px-3 py-2 text-xs">Editar proveedor</a>
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
                            <div class="mt-1 font-semibold text-slate-900">{{ $productosCantidadTotal }} unidades</div>
                        </div>
                        <div>
                            <div class="text-xs uppercase text-slate-400">Deuda actual</div>
                            <div class="mt-1 font-semibold {{ $deudaActualDisplay > 0 ? 'text-rose-600' : 'text-slate-900' }}">
                                ${{ number_format($deudaActualDisplay, 2, ',', '.') }}
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
                            <label class="app-label">Próxima visita</label>
                            <input type="date" name="proxima_visita" value="{{ old('proxima_visita', $proveedor->proxima_visita?->format('Y-m-d')) }}" class="app-input">
                        </div>
                        <div>
                            <label class="app-label">Tipo</label>
                            <select name="tipo" class="app-input">
                                @foreach (['Pago deuda', 'Productos', 'Visita', 'Nota', 'Otro'] as $tipo)
                                    <option value="{{ $tipo }}" @selected(old('tipo') === $tipo)>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @php
                        $productosDetalle = old('productos_detalle', [['nombre' => '', 'cantidad' => '']]);
                    @endphp
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <label class="app-label">Productos y cantidades</label>
                                <p class="text-xs text-slate-500">Sumá varios productos antes de registrar la acción.</p>
                            </div>
                            <button type="button" class="app-btn-secondary px-3 py-2 text-xs" data-add-producto>Agregar producto</button>
                        </div>
                        <div class="space-y-3" data-productos-list>
                            @foreach ($productosDetalle as $index => $detalle)
                                <div class="grid grid-cols-1 gap-3 rounded-xl border border-slate-200/70 bg-white p-3 md:grid-cols-[2fr,1fr,auto]" data-producto-row>
                                    <div>
                                        <label class="app-label">Producto</label>
                                        <input name="productos_detalle[{{ $index }}][nombre]" value="{{ $detalle['nombre'] ?? '' }}" class="app-input" placeholder="Ej: Papas, bebidas">
                                    </div>
                                    <div>
                                        <label class="app-label">Cantidad</label>
                                        <input type="number" name="productos_detalle[{{ $index }}][cantidad]" value="{{ $detalle['cantidad'] ?? '' }}" class="app-input" min="0" placeholder="0">
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" class="app-btn-secondary px-3 py-2 text-xs" data-remove-producto>Quitar</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="rounded-xl border border-slate-200/70 bg-white p-3">
                            <label class="app-label">Pago productos</label>
                            <input type="number" name="monto_productos" value="{{ old('monto_productos') }}" class="app-input" min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div class="rounded-xl border border-slate-200/70 bg-white p-3">
                            <label class="app-label">Deuda pendiente</label>
                            <input type="number" name="deuda_pendiente" value="{{ old('deuda_pendiente') }}" class="app-input" min="0" step="0.01" placeholder="0.00">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button class="app-btn-primary px-4 py-2 text-xs" type="submit">Agregar acción</button>
                    </div>
                </form>
                <div class="mt-5 space-y-3">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Acciones</div>
                    @if ($accionesDisplay->isEmpty())
                        <div class="rounded-2xl border border-dashed border-slate-200 p-4 text-sm text-slate-500">
                            Aún no hay acciones registradas para este proveedor.
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-2xl border border-slate-200/70">
                            <table class="app-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Detalle</th>
                                        <th class="text-right">Monto</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200/70">
                                    @foreach ($accionesDisplay as $accion)
                                        @php
                                            $fechaAccion = isset($accion['fecha']) && $accion['fecha']
                                                ? \Illuminate\Support\Carbon::parse($accion['fecha'])->format('d/m/Y')
                                                : 'Sin fecha';
                                            $horaAccion = $accion['hora'] ?: 'Sin hora';
                                            $cantidadTexto = $accion['cantidad'] ?? null;
                                            $detalles = [];
                                            $tipoAccion = strtolower($accion['tipo'] ?? '');
                                            if (str_starts_with($tipoAccion, 'pago') && str_contains($tipoAccion, 'deuda')) {
                                                $detalles[] = 'Deuda pendiente pagada';
                                            }
                                            if (! empty($accion['productos'])) {
                                                $detalles[] = 'Productos: ' . $accion['productos'];
                                            }
                                            if ($cantidadTexto !== null && $cantidadTexto !== '') {
                                                $detalles[] = 'Cantidad: ' . $cantidadTexto;
                                            }
                                            if (!empty($accion['notas'])) {
                                                $detalles[] = 'Notas: ' . $accion['notas'];
                                            }
                                            if (empty($detalles)) {
                                                if (str_contains($tipoAccion, 'producto') && ! str_starts_with($tipoAccion, 'pago')) {
                                                    $detalles[] = 'Compra de productos';
                                                } elseif (str_starts_with($tipoAccion, 'pago') && str_contains($tipoAccion, 'deuda')) {
                                                    $detalles[] = 'Deuda pendiente pagada';
                                                } elseif (str_starts_with($tipoAccion, 'pago') && str_contains($tipoAccion, 'producto')) {
                                                    $detalles[] = 'Pago de productos';
                                                } elseif (str_starts_with($tipoAccion, 'pago')) {
                                                    $detalles[] = 'Pago registrado';
                                                }
                                            }
                                            $montoAccion = $accion['monto'] ?? null;
                                        @endphp
                                        <tr>
                                            <td class="font-semibold text-slate-900">#{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="text-sm text-slate-700">{{ $fechaAccion }}</div>
                                                <div class="mt-1 text-xs text-slate-500">{{ $horaAccion }}</div>
                                            </td>
                                            <td>
                                                <span class="text-sm text-slate-700">{{ $accion['tipo'] ?? 'Acción' }}</span>
                                            </td>
                                            <td>
                                                <div class="text-sm text-slate-700">
                                                    {{ $detalles ? implode(' · ', $detalles) : 'Sin detalles adicionales' }}
                                                </div>
                                            </td>
                                            <td class="text-right text-sm text-slate-700">
                                                {{ $montoAccion !== null ? '$' . number_format($montoAccion, 2, ',', '.') : '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const list = document.querySelector('[data-productos-list]');
            const addButton = document.querySelector('[data-add-producto]');

            if (!list || !addButton) {
                return;
            }

            const buildRow = (index) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'grid grid-cols-1 gap-3 rounded-xl border border-slate-200/70 bg-white p-3 md:grid-cols-[2fr,1fr,auto]';
                wrapper.setAttribute('data-producto-row', '');
                wrapper.innerHTML = `
                    <div>
                        <label class="app-label">Producto</label>
                        <input name="productos_detalle[${index}][nombre]" class="app-input" placeholder="Ej: Papas, bebidas">
                    </div>
                    <div>
                        <label class="app-label">Cantidad</label>
                        <input type="number" name="productos_detalle[${index}][cantidad]" class="app-input" min="0" placeholder="0">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="app-btn-secondary px-3 py-2 text-xs" data-remove-producto>Quitar</button>
                    </div>
                `;
                return wrapper;
            };

            const refreshRemoveButtons = () => {
                list.querySelectorAll('[data-remove-producto]').forEach((button) => {
                    button.onclick = () => {
                        if (list.children.length > 1) {
                            button.closest('[data-producto-row]').remove();
                        } else {
                            const inputs = list.querySelectorAll('input');
                            inputs.forEach((input) => {
                                input.value = '';
                            });
                        }
                    };
                });
            };

            const getNextIndex = () => {
                const indices = Array.from(list.querySelectorAll('input[name^="productos_detalle"]'))
                    .map((input) => input.name.match(/productos_detalle\[(\d+)\]/))
                    .filter(Boolean)
                    .map((match) => Number.parseInt(match[1], 10));
                return indices.length ? Math.max(...indices) + 1 : list.children.length;
            };

            addButton.addEventListener('click', () => {
                const index = getNextIndex();
                list.appendChild(buildRow(index));
                refreshRemoveButtons();
            });

            refreshRemoveButtons();
        });
    </script>
</x-app-layout>
