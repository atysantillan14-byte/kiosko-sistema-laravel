<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Editar categoría</h2>
            <p class="text-sm text-slate-500">Actualizá nombre, orden o estado.</p>
        </div>
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('categorias.update', $categoria) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <x-input name="nombre" label="Nombre" :value="old('nombre', $categoria->nombre)" required />
            <x-input name="slug" label="Slug" :value="old('slug', $categoria->slug)" />
            <x-textarea name="descripcion" label="Descripción">{{ old('descripcion', $categoria->descripcion) }}</x-textarea>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-input name="imagen" label="Imagen (URL o path)" :value="old('imagen', $categoria->imagen)" />
                <x-input name="orden" type="number" label="Orden" :value="old('orden', $categoria->orden)" min="0" />
            </div>

            <x-checkbox name="activo" label="Activa" value="1" :checked="old('activo', $categoria->activo)" />

            <div class="flex justify-end gap-2">
                <x-button variant="outline" as="a" href="{{ route('categorias.index') }}">Volver</x-button>
                <x-button type="submit">Actualizar</x-button>
            </div>
        </form>
    </x-card>
</x-app-layout>
