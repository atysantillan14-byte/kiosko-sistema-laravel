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

                    <div class="flex flex-wrap justify-end gap-2">
                        <a href="{{ route('ventas.index') }}" class="app-btn-secondary">Cancelar</a>
                        <button class="app-btn-primary" type="submit">Actualizar</button>
                    </div>
                </form>

                <div class="mt-4 text-xs text-slate-500">
                    Nota: la edición de ítems (productos) se puede agregar después (para no romper stock y totales).
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
