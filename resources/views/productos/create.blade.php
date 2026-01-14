<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Nuevo producto</h2>
                <p class="text-sm text-slate-500">Cargá los datos básicos del producto.</p>
            </div>
            <x-button variant="outline" as="a" href="{{ route('productos.index') }}">Volver</x-button>
        </div>
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('productos.store') }}" class="space-y-4">
            @csrf

            <x-select name="categoria_id" label="Categoría">
                @foreach($categorias as $c)
                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                @endforeach
            </x-select>

            <x-input name="nombre" label="Nombre" :value="old('nombre')" required />

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-input name="precio" type="number" step="0.01" label="Precio" :value="old('precio')" required />
                <x-input name="precio_descuento" type="number" step="0.01" label="Precio descuento" :value="old('precio_descuento')" />
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-input name="stock" type="number" label="Stock" :value="old('stock', 0)" required />
                <x-input name="sku" label="SKU" :value="old('sku')" placeholder="Si lo dejás vacío se genera solo" />
            </div>

            <x-textarea name="descripcion" label="Descripción">{{ old('descripcion') }}</x-textarea>

            <x-input name="imagen" label="Imagen (URL o path)" :value="old('imagen')" />

            <div class="flex flex-wrap items-center gap-6">
                <x-checkbox name="disponible" label="Disponible" value="1" checked />
                <x-checkbox name="destacado" label="Destacado" value="1" />
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <x-button variant="outline" as="a" href="{{ route('productos.index') }}">Cancelar</x-button>
                <x-button type="submit">Guardar</x-button>
            </div>
        </form>
    </x-card>
</x-app-layout>
