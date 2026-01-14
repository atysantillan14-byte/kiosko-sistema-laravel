<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Editar venta #{{ $venta->id }}</h2>
                <p class="text-sm text-slate-500">Actualizá usuario, método y estado.</p>
            </div>
            <x-button variant="outline" as="a" href="{{ route('ventas.index') }}">Volver</x-button>
        </div>
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('ventas.update', $venta) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <x-select name="user_id" label="Usuario">
                @foreach($usuarios as $u)
                    <option value="{{ $u->id }}" @selected((string)$venta->user_id === (string)$u->id)>
                        {{ $u->name }}
                    </option>
                @endforeach
            </x-select>

            <x-input name="metodo_pago" label="Método de pago" :value="old('metodo_pago', $venta->metodo_pago)" />
            <x-input name="estado" label="Estado" :value="old('estado', $venta->estado)" />

            <div class="flex justify-end gap-2 pt-2">
                <x-button variant="outline" as="a" href="{{ route('ventas.index') }}">Cancelar</x-button>
                <x-button type="submit">Actualizar</x-button>
            </div>
        </form>

        <p class="mt-4 text-xs text-slate-500">
            Nota: la edición de ítems (productos) se puede agregar después (para no romper stock y totales).
        </p>
    </x-card>
</x-app-layout>
