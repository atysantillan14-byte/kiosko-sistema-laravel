<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Productos
            </h2>

            <a href="{{ route('productos.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                Nuevo Producto
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Filtros compactos (NO ancho completo) --}}
            <div class="bg-white rounded-xl shadow-sm p-5">
                <form method="GET" action="{{ route('productos.index') }}"
                      class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-5">
                        <label class="text-sm font-semibold text-gray-700">Buscar</label>
                        <input name="q" value="{{ $q ?? '' }}"
                               placeholder="Nombre o SKU..."
                               class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    </div>

                    <div class="md:col-span-4">
                        <label class="text-sm font-semibold text-gray-700">Categoría</label>
                        <select name="categoria_id"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todas</option>
                            @foreach($categorias as $c)
                                <option value="{{ $c->id }}" @selected((string)$categoriaId === (string)$c->id)>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-3 flex gap-2">
                        <button class="w-full px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black">
                            Buscar
                        </button>
                        <a href="{{ route('productos.index') }}"
                           class="w-full px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-center">
                            Limpiar
                        </a>
                    </div>
                </form>

                <div class="mt-3 text-xs text-gray-500">
                    Tip: hacé click en una categoría en “Categorías” para ver sus productos automáticamente.
                </div>
            </div>

            {{-- Tabla ancho completo --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-semibold text-gray-600">
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Categoría</th>
                            <th class="px-6 py-4">SKU</th>
                            <th class="px-6 py-4">Precio</th>
                            <th class="px-6 py-4">Stock</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                        @forelse($productos as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $p->nombre }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $p->categoria?->nombre ?? 'Sin categoría' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $p->sku ?? '-' }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    $ {{ number_format((float)$p->precio, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                                        {{ (int)$p->stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('productos.edit', $p) }}"
                                           class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('productos.destroy', $p) }}"
                                              onsubmit="return confirm('¿Eliminar este producto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-gray-600" colspan="6">
                                    No hay productos para mostrar.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $productos->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>


