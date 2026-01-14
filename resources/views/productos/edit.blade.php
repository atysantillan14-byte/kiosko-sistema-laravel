<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Editar producto</h2>
                <p class="text-sm text-slate-500">Actualizá los datos del producto seleccionado.</p>
            </div>
            <x-button variant="outline" as="a" href="{{ route('productos.index') }}">Volver</x-button>
        </div>
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('productos.update', $producto) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <x-select name="categoria_id" label="Categoría" required>
                <option value="">Seleccione...</option>
                @foreach($categorias as $c)
                    <option value="{{ $c->id }}" @selected(old('categoria_id', $producto->categoria_id) == $c->id)>
                        {{ $c->nombre }}
                    </option>
                @endforeach
            </x-select>

            <x-input name="nombre" label="Nombre" :value="old('nombre', $producto->nombre)" required />

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-input name="precio" type="number" step="0.01" label="Precio" :value="old('precio', $producto->precio)" required />
                <x-input name="precio_descuento" type="number" step="0.01" label="Precio descuento" :value="old('precio_descuento', $producto->precio_descuento)" />
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-input name="stock" type="number" label="Stock" :value="old('stock', $producto->stock)" />
                <x-input name="sku" label="SKU" :value="old('sku', $producto->sku)" />
            </div>

            <x-textarea name="descripcion" label="Descripción">{{ old('descripcion', $producto->descripcion) }}</x-textarea>

            <x-input name="imagen" label="Imagen (URL o path)" :value="old('imagen', $producto->imagen)" />

            <div class="flex flex-wrap items-center gap-6">
                <x-checkbox name="disponible" label="Disponible" value="1" :checked="old('disponible', $producto->disponible)" />
                <x-checkbox name="destacado" label="Destacado" value="1" :checked="old('destacado', $producto->destacado)" />
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <x-button variant="outline" as="a" href="{{ route('productos.index') }}">Cancelar</x-button>
                <x-button type="submit">Actualizar</x-button>
            </div>
        </form>
    </x-card>
</x-app-layout>
