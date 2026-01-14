<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categorías
            </h2>

            <a href="{{ route('categorias.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                Nueva
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-semibold text-gray-600">
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Slug</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4">Orden</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @foreach($categorias as $cat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <a class="font-semibold text-gray-900 hover:text-blue-600"
                                       href="{{ route('productos.index', ['categoria_id' => $cat->id]) }}">
                                        {{ $cat->nombre }}
                                    </a>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Ver productos de esta categoría
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $cat->slug }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ ($cat->estado ?? 'activa') === 'activa' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $cat->estado ?? 'activa' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $cat->orden ?? 0 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('categorias.edit', $cat) }}"
                                           class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('categorias.destroy', $cat) }}"
                                              onsubmit="return confirm('¿Eliminar esta categoría?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>



