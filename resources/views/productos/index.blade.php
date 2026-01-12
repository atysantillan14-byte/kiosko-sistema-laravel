<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Productos</h2>
            <a href="{{ route('productos.create') }}"
               class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                Nuevo
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">Producto</th>
                                <th class="py-2">Categoría</th>
                                <th class="py-2">Precio</th>
                                <th class="py-2">Stock</th>
                                <th class="py-2">Disponible</th>
                                <th class="py-2 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productos as $p)
                                <tr class="border-b">
                                    <td class="py-2 font-medium">{{ $p->nombre }}</td>
                                    <td class="py-2 text-gray-600">{{ $p->categoria?->nombre }}</td>
                                    <td class="py-2">$ {{ number_format((float)$p->precio, 2, ',', '.') }}</td>
                                    <td class="py-2">{{ $p->stock }}</td>
                                    <td class="py-2">{{ $p->disponible ? 'Sí' : 'No' }}</td>
                                    <td class="py-2 text-right">
                                        <a href="{{ route('productos.edit', $p) }}"
                                           class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">
                                            Editar
                                        </a>

                                        <form action="{{ route('productos.destroy', $p) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('¿Eliminar este producto?')"
                                                    class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-gray-500">
                                        No hay productos todavía.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

