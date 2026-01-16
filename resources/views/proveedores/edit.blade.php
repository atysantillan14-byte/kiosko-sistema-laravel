<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Editar proveedor</h2>
                <p class="app-subtitle">Actualizá la información de contacto y acuerdos.</p>
            </div>
            <a href="{{ route('proveedores.index') }}" class="app-btn-secondary">Volver</a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-4xl">
            <div class="app-card p-6 sm:p-8">
                @if (session('error'))
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        {{ session('error') }}
                    </div>
                @endif
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

                <form method="POST" action="{{ route('proveedores.update', $proveedor) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="app-label">Nombre comercial</label>
                        <input name="nombre" value="{{ old('nombre', $proveedor->nombre) }}" class="app-input @error('nombre') app-input-error @enderror" required>
                        @error('nombre')<div class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="app-label">Contacto principal</label>
                            <input name="contacto" value="{{ old('contacto', $proveedor->contacto) }}" class="app-input" placeholder="Nombre y apellido">
                        </div>

                        <div>
                            <label class="app-label">Teléfono</label>
                            <input name="telefono" value="{{ old('telefono', $proveedor->telefono) }}" class="app-input" placeholder="+54 11 5555-5555">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="app-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $proveedor->email) }}" class="app-input" placeholder="proveedor@email.com">
                        </div>

                        <div>
                            <label class="app-label">Dirección</label>
                            <input name="direccion" value="{{ old('direccion', $proveedor->direccion) }}" class="app-input" placeholder="Av. Siempreviva 123">
                        </div>
                    </div>

                    <div>
                        <label class="app-label">Condiciones de pago</label>
                        <input name="condiciones_pago" value="{{ old('condiciones_pago', $proveedor->condiciones_pago) }}" class="app-input" placeholder="Ej: 15 días, transferencia bancaria">
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-[2fr,1fr]">
                        <div>
                            <label class="app-label">Productos que trae</label>
                            <textarea name="productos" class="app-input" rows="3" placeholder="Ej: Snacks, bebidas, golosinas...">{{ old('productos', $proveedor->productos) }}</textarea>
                        </div>

                        <div>
                            <label class="app-label">Cantidad estimada</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad', $proveedor->cantidad) }}" class="app-input" min="0" placeholder="Cantidad">
                        </div>
                    </div>

                    <div>
                        <label class="app-label">Notas internas</label>
                        <textarea name="notas" class="app-input" rows="3" placeholder="Comentarios o acuerdos especiales.">{{ old('notas', $proveedor->notas) }}</textarea>
                    </div>

                    <div class="flex items-center gap-2 rounded-xl border border-slate-200/70 bg-slate-50/80 px-4 py-3 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="activo" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-200" {{ old('activo', $proveedor->activo) ? 'checked' : '' }}>
                        Activo
                    </div>

                    <div class="flex flex-wrap justify-end gap-2">
                        <a href="{{ route('proveedores.index') }}" class="app-btn-secondary">Cancelar</a>
                        <button class="app-btn-primary" type="submit">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
