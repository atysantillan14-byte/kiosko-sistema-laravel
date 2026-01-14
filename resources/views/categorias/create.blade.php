<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Nueva categoría</h2>
            <p class="text-sm text-slate-500">Definí nombre, orden y estado.</p>
        </div>
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('categorias.store') }}" class="space-y-4">
            @csrf

            <x-input name="nombre" label="Nombre" :value="old('nombre')" required />
            <x-input name="slug" label="Slug (opcional)" :value="old('slug')" placeholder="se-genera-solo-si-lo-dejas-vacio" />
            <x-textarea name="descripcion" label="Descripción">{{ old('descripcion') }}</x-textarea>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-input name="imagen" label="Imagen (URL o path)" :value="old('imagen')" />
                <x-input name="orden" type="number" label="Orden" :value="old('orden', 0)" min="0" />
            </div>

            <x-checkbox name="activo" label="Activa" value="1" :checked="old('activo', true)" />

            <div class="flex justify-end gap-2">
                <x-button variant="outline" as="a" href="{{ route('categorias.index') }}">Volver</x-button>
                <x-button type="submit">Guardar</x-button>
            </div>
        </form>
    </x-card>
</x-app-layout>
