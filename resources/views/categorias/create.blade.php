<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Nueva categoría</h2>
                <p class="app-subtitle">Definí la estructura de tu catálogo con claridad.</p>
            </div>
            <a href="{{ route('categorias.index') }}" class="app-btn-secondary">Volver</a>
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

                <form method="POST" action="{{ route('categorias.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="app-label">Nombre</label>
                        <input name="nombre" value="{{ old('nombre') }}" class="app-input @error('nombre') app-input-error @enderror" required>
                        @error('nombre')<div class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="app-label">Slug (opcional)</label>
                        <input name="slug" value="{{ old('slug') }}" class="app-input" placeholder="se-genera-solo-si-lo-dejas-vacio">
                        <p class="app-helper mt-1">Se genera automáticamente si queda vacío.</p>
                    </div>

                    <div>
                        <label class="app-label">Descripción</label>
                        <textarea name="descripcion" class="app-input" rows="3">{{ old('descripcion') }}</textarea>
                    </div>

                    <div>
                        <label class="app-label">Imagen (URL o path)</label>
                        <input name="imagen" value="{{ old('imagen') }}" class="app-input">
                    </div>

                    <div class="flex items-center gap-2 rounded-xl border border-slate-200/70 bg-slate-50/80 px-4 py-3 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="activo" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-200" {{ old('activo', true) ? 'checked' : '' }}>
                        Activa
                    </div>

                    <div class="flex flex-wrap justify-end gap-2">
                        <a href="{{ route('categorias.index') }}" class="app-btn-secondary">Cancelar</a>
                        <button class="app-btn-primary" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
