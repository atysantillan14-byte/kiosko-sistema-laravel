<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Nuevo producto</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('productos.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium">Categoría</label>
                        <select name="categoria_id" class="mt-1 w-full rounded border-gray-300" required>
                            <option value="">Seleccione...</option>
                            @foreach($categorias as $c)
                                <option value="{{ $c->id }}" @selected(old('categoria_id') == $c->id)>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Nombre</label>
                        <input name="nombre" value="{{ old('nombre') }}"
                               class="mt-1 w-full rounded border-gray-300" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Precio</label>
                            <input type="number" step="0.01" name="precio" value="{{ old('precio') }}"
                                   class="mt-1 w-full rounded border-gray-300" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Precio descuento (opcional)</label>
                            <input type="number" step="0.01" name="precio_descuento" value="{{ old('precio_descuento') }}"
                                   class="mt-1 w-full rounded border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Stock</label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                                   class="mt-1 w-full rounded border-gray-300" min="0">
                        </div>

                        <div>
                            <label class="block text-sm font-medium">SKU (opcional)</label>
                            <input name="sku" value="{{ old('sku') }}"
                                   class="mt-1 w-full rounded border-gray-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Descripción</label>
                        <textarea name="descripcion" rows="3"
                                  class="mt-1 w-full rounded border-gray-300">{{ old('descripcion') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Imagen (URL o path)</label>
                        <input name="imagen" value="{{ old('imagen') }}"
                               class="mt-1 w-full rounded border-gray-300">
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="disponible" value="1" class="rounded border-gray-300"
                                   {{ old('disponible', true) ? 'checked' : '' }}>
                            <span>Disponible</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="destacado" value="1" class="rounded border-gray-300"
                                   {{ old('destacado', false) ? 'checked' : '' }}>
                            <span>Destacado</span>
                        </label>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('productos.index') }}" class="px-4 py-2 rounded border">
                            Volver
                        </a>
                        <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                            Guardar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

