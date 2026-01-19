<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Editar producto</h2>
                <p class="app-subtitle">Actualizá los datos y mantené el inventario al día.</p>
            </div>
            <a href="{{ route('productos.index') }}" class="app-btn-secondary">Volver</a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-4xl">
            <div class="app-card p-6 sm:p-8">
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        <div class="font-semibold">Revisá los errores antes de continuar</div>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('productos.update', $producto) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="app-label">Categoría</label>
                        <select name="categoria_id" class="app-input @error('categoria_id') app-input-error @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($categorias as $c)
                                <option value="{{ $c->id }}" @selected(old('categoria_id', $producto->categoria_id) == $c->id)>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')<div class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="app-label">Nombre</label>
                        <input name="nombre" value="{{ old('nombre', $producto->nombre) }}" class="app-input @error('nombre') app-input-error @enderror" required>
                        @error('nombre')<div class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="app-label">Precio</label>
                            <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" class="app-input @error('precio') app-input-error @enderror" required>
                            @error('precio')<div class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="app-label">Precio descuento</label>
                            <input type="number" step="0.01" name="precio_descuento" value="{{ old('precio_descuento', $producto->precio_descuento) }}" class="app-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="app-label">Stock</label>
                            <input type="number" name="stock" min="0" step="0.01" value="{{ old('stock', $producto->stock) }}" class="app-input @error('stock') app-input-error @enderror">
                            @error('stock')<div class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="app-label">SKU</label>
                            <input name="sku" value="{{ old('sku', $producto->sku) }}" class="app-input">
                        </div>
                    </div>

                    <div>
                        <label class="app-label">Descripción</label>
                        <textarea name="descripcion" rows="3" class="app-input">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    </div>

                    <div>
                        <label class="app-label">Imagen (URL o path)</label>
                        <input name="imagen" value="{{ old('imagen', $producto->imagen) }}" class="app-input">
                    </div>

                    <div class="flex flex-wrap items-center gap-6 rounded-2xl border border-slate-200/70 bg-slate-50/80 px-4 py-3">
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <input type="checkbox" name="disponible" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-200" {{ old('disponible', $producto->disponible) ? 'checked' : '' }}>
                            Disponible
                        </label>

                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <input type="checkbox" name="destacado" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-200" {{ old('destacado', $producto->destacado) ? 'checked' : '' }}>
                            Destacado
                        </label>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2">
                        <a href="{{ route('productos.index') }}" class="app-btn-secondary">Cancelar</a>
                        <button type="submit" class="app-btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
