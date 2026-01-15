<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Nuevo producto</h2>
                <p class="app-subtitle">Completá los datos principales para registrar el producto.</p>
            </div>
            <a href="{{ route('productos.index') }}" class="app-btn-secondary">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-4xl">
            <div class="app-card p-6 sm:p-8">
                <form method="POST" action="{{ route('productos.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="app-label">Categoría</label>
                        <select name="categoria_id" class="app-input @error('categoria_id') app-input-error @enderror">
                            @foreach($categorias as $c)
                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                        @error('categoria_id')<div class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="app-label">Nombre</label>
                        <input name="nombre" value="{{ old('nombre') }}" class="app-input @error('nombre') app-input-error @enderror" />
                        @error('nombre')<div class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="app-label">Precio</label>
                            <input name="precio" value="{{ old('precio') }}" type="number" step="0.01" class="app-input @error('precio') app-input-error @enderror" />
                            @error('precio')<div class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="app-label">Precio descuento</label>
                            <input name="precio_descuento" value="{{ old('precio_descuento') }}" type="number" step="0.01" class="app-input" />
                            <p class="app-helper mt-1">Opcional para promociones.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="app-label">Stock</label>
                            <input name="stock" value="{{ old('stock', 0) }}" type="number" class="app-input @error('stock') app-input-error @enderror" />
                            @error('stock')<div class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="app-label">SKU</label>
                            <input name="sku" value="{{ old('sku') }}" placeholder="Si lo dejás vacío se genera solo" class="app-input" />
                        </div>
                    </div>

                    <div>
                        <label class="app-label">Descripción</label>
                        <textarea name="descripcion" rows="4" class="app-input">{{ old('descripcion') }}</textarea>
                    </div>

                    <div>
                        <label class="app-label">Imagen (URL o path)</label>
                        <input name="imagen" value="{{ old('imagen') }}" class="app-input" />
                    </div>

                    <div class="flex flex-wrap items-center gap-6 rounded-2xl border border-slate-200/70 bg-slate-50/80 px-4 py-3">
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <input type="checkbox" name="disponible" value="1" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-200">
                            Disponible
                        </label>

                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <input type="checkbox" name="destacado" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-200">
                            Destacado
                        </label>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2">
                        <a href="{{ route('productos.index') }}" class="app-btn-secondary">
                            Cancelar
                        </a>
                        <button class="app-btn-primary" type="submit">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
