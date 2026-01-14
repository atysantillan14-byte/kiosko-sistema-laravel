<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Editar producto
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- Errores de validación --}}
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('productos.update', $producto) }}"
                      class="space-y-4">

                    @csrf
                    @method('PUT')

                    {{-- Categoría --}}
                    <div>
                        <label class="block text-sm font-medium">Categoría</label>
                        <select name="categoria_id"
                                class="mt-1 w-full rounded border-gray-300"
                                required>
                            <option value="">Seleccione...</option>
                            @foreach($categorias as $c)
                                <option value="{{ $c->id }}"
                                    @selected(old('categoria_id', $producto->categoria_id) == $c->id)>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nombre --}}
                    <div>
                        <label class="block text-sm font-medium">Nombre</label>
                        <input name="nombre"
                               value="{{ old('nombre', $producto->nombre) }}"
                               class="mt-1 w-full rounded border-gray-300"
                               required>
                    </div>

                    {{-- Precios --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Precio</label>
                            <input type="number"
                                   step="0.01"
                                   name="precio"
                                   value="{{ old('precio', $producto->precio) }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Precio descuento</label>
                            <input type="number"
                                   step="0.01"
                                   name="precio_descuento"
                                   value="{{ old('precio_descuento', $producto->precio_descuento) }}"
                                   class="mt-1 w-full rounded border-gray-300">
                        </div>
                    </div>

                    {{-- Stock / SKU --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Stock</label>
                            <input type="number"
                                   name="stock"
                                   min="0"
                                   value="{{ old('stock', $producto->stock) }}"
                                   class="mt-1 w-full rounded border-gray-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium">SKU</label>
                            <input name="sku"
                                   value="{{ old('sku', $producto->sku) }}"
                                   class="mt-1 w-full rounded border-gray-300">
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label class="block text-sm font-medium">Descripción</label>
                        <textarea name="descripcion"
                                  rows="3"
                                  class="mt-1 w-full rounded border-gray-300">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    </div>

                    {{-- Imagen --}}
                    <div>
                        <label class="block text-sm font-medium">Imagen (URL o path)</label>
                        <input name="imagen"
                               value="{{ old('imagen', $producto->imagen) }}"
                               class="mt-1 w-full rounded border-gray-300">
                    </div>

                    {{-- Checkboxes --}}
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="disponible"
                                   value="1"
                                   class="rounded border-gray-300"
                                   {{ old('disponible', $producto->disponible) ? 'checked' : '' }}>
                            <span>Disponible</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="destacado"
                                   value="1"
                                   class="rounded border-gray-300"
                                   {{ old('destacado', $producto->destacado) ? 'checked' : '' }}>
                            <span>Destacado</span>
                        </label>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex gap-2 pt-4">
                        <a href="{{ route('productos.index') }}"
                           class="px-4 py-2 rounded border">
                            Volver
                        </a>

                        <button type="submit"
                                class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                            Actualizar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
