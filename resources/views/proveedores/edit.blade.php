<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Editar proveedor</h2>
                <p class="app-subtitle">Actualizá la información de contacto y acuerdos.</p>
            </div>
            <a href="{{ route('proveedores.index') }}" class="app-btn-secondary">Volver</a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-4xl">
            <div class="app-card p-6 sm:p-8">
                @if (session('error'))
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        <div class="font-semibold">Revisá los errores antes de continuar</div>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('proveedores.update', $proveedor) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="app-label">Nombre comercial</label>
                        <input name="nombre" value="{{ old('nombre', $proveedor->nombre) }}" class="app-input @error('nombre') app-input-error @enderror" required>
                        @error('nombre')<div class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="app-label">Contacto principal</label>
                            <input name="contacto" value="{{ old('contacto', $proveedor->contacto) }}" class="app-input" placeholder="Nombre y apellido">
                        </div>

                        <div>
                            <label class="app-label">Teléfono</label>
                            <input name="telefono" value="{{ old('telefono', $proveedor->telefono) }}" class="app-input" placeholder="+54 11 5555-5555">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="app-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $proveedor->email) }}" class="app-input" placeholder="proveedor@email.com">
                        </div>

                        <div>
                            <label class="app-label">Dirección</label>
                            <input name="direccion" value="{{ old('direccion', $proveedor->direccion) }}" class="app-input" placeholder="Av. Siempreviva 123">
                        </div>
                    </div>

                    <div>
                        <label class="app-label">Condiciones de pago</label>
                        <input name="condiciones_pago" value="{{ old('condiciones_pago', $proveedor->condiciones_pago) }}" class="app-input" placeholder="Ej: 15 días, transferencia bancaria">
                    </div>

                    @php
                        $accionesDetalle = old('acciones', $proveedor->acciones ?? [[
                            'fecha' => '',
                            'hora' => '',
                            'tipo' => '',
                            'productos' => '',
                            'cantidad' => '',
                            'monto' => '',
                            'notas' => '',
                        ]]);
                    @endphp
                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <label class="app-label">Acciones del proveedor</label>
                                <p class="text-xs text-slate-500">Sumá cada visita o entrega como una acción con sus productos y notas.</p>
                            </div>
                            <button type="button" class="app-btn-secondary px-3 py-2 text-xs" data-add-accion>Agregar acción</button>
                        </div>
                        <div class="space-y-3" data-acciones-list>
                            @foreach ($accionesDetalle as $index => $accion)
                                <div class="rounded-xl border border-slate-200/70 bg-slate-50/60 p-3" data-accion-row>
                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                                        <div>
                                            <label class="app-label">Fecha</label>
                                            <input type="date" name="acciones[{{ $index }}][fecha]" value="{{ $accion['fecha'] ?? '' }}" class="app-input">
                                        </div>
                                        <div>
                                            <label class="app-label">Hora</label>
                                            <input type="time" name="acciones[{{ $index }}][hora]" value="{{ $accion['hora'] ?? '' }}" class="app-input">
                                        </div>
                                        <div>
                                            <label class="app-label">Tipo</label>
                                            <input name="acciones[{{ $index }}][tipo]" value="{{ $accion['tipo'] ?? '' }}" class="app-input" placeholder="Pago, productos, visita...">
                                        </div>
                                        <div>
                                            <label class="app-label">Monto</label>
                                            <input type="number" name="acciones[{{ $index }}][monto]" value="{{ $accion['monto'] ?? '' }}" class="app-input" min="0" step="0.01" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-4">
                                        <div class="md:col-span-2">
                                            <label class="app-label">Productos</label>
                                            <input name="acciones[{{ $index }}][productos]" value="{{ $accion['productos'] ?? '' }}" class="app-input" placeholder="Ej: Papas, bebidas, golosinas">
                                        </div>
                                        <div>
                                            <label class="app-label">Cantidad</label>
                                            <input type="number" name="acciones[{{ $index }}][cantidad]" value="{{ $accion['cantidad'] ?? '' }}" class="app-input" min="0" placeholder="0">
                                        </div>
                                        <div>
                                            <label class="app-label">Notas</label>
                                            <input name="acciones[{{ $index }}][notas]" value="{{ $accion['notas'] ?? '' }}" class="app-input" placeholder="Entrega, pagos, pendientes">
                                        </div>
                                    </div>
                                    <div class="mt-3 flex justify-end">
                                        <button type="button" class="app-btn-secondary px-3 py-2 text-xs" data-remove-accion>Quitar</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @php
                        $productosDetalle = old('productos_detalle', $proveedor->productos_detalle ?? [['nombre' => '', 'cantidad' => '']]);
                    @endphp
                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <label class="app-label">Productos y cantidades</label>
                                <p class="text-xs text-slate-500">Sumá varios productos con su cantidad correspondiente.</p>
                            </div>
                            <button type="button" class="app-btn-secondary px-3 py-2 text-xs" data-add-producto>Agregar producto</button>
                        </div>
                        <div class="space-y-3" data-productos-list>
                            @foreach ($productosDetalle as $index => $detalle)
                                <div class="grid grid-cols-1 gap-3 rounded-xl border border-slate-200/70 bg-slate-50/60 p-3 md:grid-cols-[2fr,1fr,auto]" data-producto-row>
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

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-[2fr,1fr]">
                        <div>
                            <label class="app-label">Productos (texto libre)</label>
                            <textarea name="productos" class="app-input" rows="3" placeholder="Ej: Snacks, bebidas, golosinas...">{{ old('productos', $proveedor->productos) }}</textarea>
                        </div>

                        <div>
                            <label class="app-label">Cantidad estimada</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad', $proveedor->cantidad) }}" class="app-input" min="0" placeholder="Cantidad">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div>
                            <label class="app-label">Próxima visita</label>
                            <input type="date" name="proxima_visita" value="{{ old('proxima_visita', optional($proveedor->proxima_visita)->format('Y-m-d')) }}" class="app-input">
                        </div>
                        <div>
                            <label class="app-label">Hora estimada</label>
                            <input type="time" name="hora" value="{{ old('hora', $proveedor->hora) }}" class="app-input">
                        </div>
                        <div>
                            <label class="app-label">Pago realizado</label>
                            <input type="number" name="pago" value="{{ old('pago', $proveedor->pago) }}" class="app-input" min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div>
                            <label class="app-label">Deuda pendiente</label>
                            <input type="number" name="deuda" value="{{ old('deuda', $proveedor->deuda) }}" class="app-input" min="0" step="0.01" placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label class="app-label">Notas internas</label>
                        <textarea name="notas" class="app-input" rows="3" placeholder="Comentarios o acuerdos especiales.">{{ old('notas', $proveedor->notas) }}</textarea>
                    </div>

                    <div class="flex items-center gap-2 rounded-xl border border-slate-200/70 bg-slate-50/80 px-4 py-3 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="activo" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-200" {{ old('activo', $proveedor->activo) ? 'checked' : '' }}>
                        Activo
                    </div>

                    <div class="flex flex-wrap justify-end gap-2">
                        <a href="{{ route('proveedores.index') }}" class="app-btn-secondary">Cancelar</a>
                        <button class="app-btn-primary" type="submit">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const list = document.querySelector('[data-productos-list]');
            const addButton = document.querySelector('[data-add-producto]');
            const accionesList = document.querySelector('[data-acciones-list]');
            const addAccionButton = document.querySelector('[data-add-accion]');
            const condicionesInput = document.querySelector('input[name="condiciones_pago"]');
            const fechaVisitaInput = document.querySelector('input[name="fecha_visita"]');
            const proximaVisitaInput = document.querySelector('input[name="proxima_visita"]');

            const parseDaysFromText = (text) => {
                const match = text.match(/(\d+)/);
                if (!match) {
                    return null;
                }
                const days = Number.parseInt(match[1], 10);
                return Number.isFinite(days) && days > 0 ? days : null;
            };

            const addDays = (baseDate, days) => {
                const date = new Date(`${baseDate}T00:00:00`);
                if (Number.isNaN(date.getTime())) {
                    return null;
                }
                date.setDate(date.getDate() + days);
                return date.toISOString().slice(0, 10);
            };

            const updateProximaVisita = () => {
                if (!condicionesInput || !proximaVisitaInput) {
                    return;
                }
                const days = parseDaysFromText(condicionesInput.value || '');
                if (!days) {
                    return;
                }
                const baseValue = fechaVisitaInput?.value || new Date().toISOString().slice(0, 10);
                const nextDate = addDays(baseValue, days);
                if (nextDate) {
                    proximaVisitaInput.value = nextDate;
                }
            };

            if (condicionesInput) {
                condicionesInput.addEventListener('input', updateProximaVisita);
                condicionesInput.addEventListener('blur', updateProximaVisita);
            }

            if (fechaVisitaInput) {
                fechaVisitaInput.addEventListener('change', updateProximaVisita);
            }

            if (list && addButton) {
                const buildRow = (index) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'grid grid-cols-1 gap-3 rounded-xl border border-slate-200/70 bg-slate-50/60 p-3 md:grid-cols-[2fr,1fr,auto]';
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
            }

            if (accionesList && addAccionButton) {
                const buildAccionRow = (index) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'rounded-xl border border-slate-200/70 bg-slate-50/60 p-3';
                    wrapper.setAttribute('data-accion-row', '');
                    wrapper.innerHTML = `
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                            <div>
                                <label class="app-label">Fecha</label>
                                <input type="date" name="acciones[${index}][fecha]" class="app-input">
                            </div>
                            <div>
                                <label class="app-label">Hora</label>
                                <input type="time" name="acciones[${index}][hora]" class="app-input">
                            </div>
                            <div>
                                <label class="app-label">Tipo</label>
                                <input name="acciones[${index}][tipo]" class="app-input" placeholder="Pago, productos, visita...">
                            </div>
                            <div>
                                <label class="app-label">Monto</label>
                                <input type="number" name="acciones[${index}][monto]" class="app-input" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>
                        <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-4">
                            <div class="md:col-span-2">
                                <label class="app-label">Productos</label>
                                <input name="acciones[${index}][productos]" class="app-input" placeholder="Ej: Papas, bebidas, golosinas">
                            </div>
                            <div>
                                <label class="app-label">Cantidad</label>
                                <input type="number" name="acciones[${index}][cantidad]" class="app-input" min="0" placeholder="0">
                            </div>
                            <div>
                                <label class="app-label">Notas</label>
                                <input name="acciones[${index}][notas]" class="app-input" placeholder="Entrega, pagos, pendientes">
                            </div>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <button type="button" class="app-btn-secondary px-3 py-2 text-xs" data-remove-accion>Quitar</button>
                        </div>
                    `;
                    return wrapper;
                };

                const refreshAccionRemoveButtons = () => {
                    accionesList.querySelectorAll('[data-remove-accion]').forEach((button) => {
                        button.onclick = () => {
                            if (accionesList.children.length > 1) {
                                button.closest('[data-accion-row]').remove();
                            } else {
                                const inputs = accionesList.querySelectorAll('input');
                                inputs.forEach((input) => {
                                    input.value = '';
                                });
                            }
                        };
                    });
                };

                const getNextAccionIndex = () => {
                    const indices = Array.from(accionesList.querySelectorAll('input[name^="acciones"]'))
                        .map((input) => input.name.match(/acciones\[(\d+)\]/))
                        .filter(Boolean)
                        .map((match) => Number.parseInt(match[1], 10));
                    return indices.length ? Math.max(...indices) + 1 : accionesList.children.length;
                };

                addAccionButton.addEventListener('click', () => {
                    const index = getNextAccionIndex();
                    accionesList.appendChild(buildAccionRow(index));
                    refreshAccionRemoveButtons();
                });

                refreshAccionRemoveButtons();
            }
        });
    </script>
</x-app-layout>
