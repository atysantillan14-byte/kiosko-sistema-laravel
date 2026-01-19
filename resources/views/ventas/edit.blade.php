<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Editar venta #{{ $venta->id }}</h2>
                <p class="app-subtitle">Actualizá datos administrativos de la venta.</p>
            </div>
            <a href="{{ route('ventas.index') }}" class="app-btn-secondary">Volver</a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-3xl">
            <div class="app-card p-6">
                <form method="POST" action="{{ route('ventas.update', $venta) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @if ($esAdmin)
                        <div>
                            <label class="app-label">Usuario</label>
                            <select name="user_id" class="app-input">
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}" @selected((string)$venta->user_id === (string)$u->id)>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div>
                            <label class="app-label">Usuario</label>
                            <input class="app-input" value="{{ $venta->usuario?->name }}" readonly>
                        </div>
                        <input type="hidden" name="user_id" value="{{ $venta->user_id }}">
                    @endif

                    <div>
                        <label class="app-label">Método de pago</label>
                        <input name="metodo_pago" value="{{ old('metodo_pago', $venta->metodo_pago) }}" class="app-input" />
                    </div>

                    <div>
                        <label class="app-label">Estado</label>
                        <input name="estado" value="{{ old('estado', $venta->estado) }}" class="app-input" />
                    </div>

                    <div class="space-y-3">
                        <h3 class="text-sm font-semibold text-slate-900">Items de la venta</h3>
                        <div class="overflow-x-auto rounded-2xl border border-slate-200/70">
                            <table class="app-table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-right">Precio</th>
                                        <th class="text-right">Cantidad</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200/70">
                                    @foreach($venta->detalles as $index => $detalle)
                                        <tr>
                                            <td class="font-semibold text-slate-900">{{ $detalle->producto?->nombre ?? 'Producto eliminado' }}</td>
                                            <td class="text-right">$ {{ number_format((float) $detalle->precio_unitario, 2, ',', '.') }}</td>
                                            <td class="text-right">
                                                <input
                                                    type="text"
                                                    inputmode="decimal"
                                                    pattern="[0-9]+([,\\.][0-9]{1,2})?"
                                                    name="items[{{ $index }}][cantidad]"
                                                    value="{{ number_format((float) $detalle->cantidad, 2, ',', '.') }}"
                                                    class="app-input w-24 text-right"
                                                />
                                                <input type="hidden" name="items[{{ $index }}][detalle_id]" value="{{ $detalle->id }}">
                                            </td>
                                            <td class="text-right font-semibold text-slate-900">$ {{ number_format((float) $detalle->subtotal, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-xs text-slate-500">Al actualizar, se recalcula el total y el stock en base a las cantidades nuevas.</p>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2">
                        <a href="{{ route('ventas.index') }}" class="app-btn-secondary">Cancelar</a>
                        <button class="app-btn-primary" type="submit">Actualizar</button>
                    </div>
                </form>

                <div class="mt-4 text-xs text-slate-500">
                    Nota: Si ajustás las cantidades, verificá que los totales y el efectivo registrado queden consistentes.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
