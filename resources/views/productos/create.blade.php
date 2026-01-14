<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nuevo Producto
            </h2>

            <a href="{{ route('productos.index') }}"
               class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ route('productos.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Categoría</label>
                        <select name="categoria_id" class="mt-1 w-full rounded-lg border-gray-300">
                            @foreach($categorias as $c)
                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                        @error('categoria_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nombre</label>
                        <input name="nombre" value="{{ old('nombre') }}"
                               class="mt-1 w-full rounded-lg border-gray-300" />
                        @error('nombre')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Precio</label>
                            <input name="precio" value="{{ old('precio') }}" type="number" step="0.01"
                                   class="mt-1 w-full rounded-lg border-gray-300" />
                            @error('precio')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Precio descuento</label>
                            <input name="precio_descuento" value="{{ old('precio_descuento') }}" type="number" step="0.01"
                                   class="mt-1 w-full rounded-lg border-gray-300" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Stock</label>
                            <input name="stock" value="{{ old('stock', 0) }}" type="number"
                                   class="mt-1 w-full rounded-lg border-gray-300" />
                            @error('stock')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">SKU</label>
                            <input name="sku" value="{{ old('sku') }}"
                                   placeholder="Si lo dejás vacío se genera solo"
                                   class="mt-1 w-full rounded-lg border-gray-300" />
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Descripción</label>
                        <textarea name="descripcion" rows="4"
                                  class="mt-1 w-full rounded-lg border-gray-300">{{ old('descripcion') }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Imagen (URL o path)</label>
                        <input name="imagen" value="{{ old('imagen') }}"
                               class="mt-1 w-full rounded-lg border-gray-300" />
                    </div>

                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="disponible" value="1" checked class="rounded border-gray-300">
                            <span class="text-sm text-gray-700 font-semibold">Disponible</span>
                        </label>

                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="destacado" value="1" class="rounded border-gray-300">
                            <span class="text-sm text-gray-700 font-semibold">Destacado</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('productos.index') }}"
                           class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                            Cancelar
                        </a>
                        <button class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

