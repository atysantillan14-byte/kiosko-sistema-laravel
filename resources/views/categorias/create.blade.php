<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Nueva categoría</h2>
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

                <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium">Nombre</label>
                        <input name="nombre" value="{{ old('nombre') }}"
                               class="mt-1 w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Descripción</label>
                        <textarea name="descripcion" class="mt-1 w-full rounded border-gray-300"
                                  rows="3">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Imagen (URL o path)</label>
                            <input name="imagen" value="{{ old('imagen') }}"
                                   class="mt-1 w-full rounded border-gray-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Orden</label>
                            <input type="number" name="orden" value="{{ old('orden', 0) }}"
                                   class="mt-1 w-full rounded border-gray-300" min="0">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="activo" value="1" checked
                               class="rounded border-gray-300">
                        <span>Activa</span>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('categories.index') }}"
                           class="px-4 py-2 rounded border">
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
