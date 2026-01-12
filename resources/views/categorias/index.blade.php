<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Categorías</h2>

            <a href="{{ route('categorias.create') }}"
               class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                Nueva
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
                                <th class="py-2">Nombre</th>
                                <th class="py-2">Slug</th>
                                <th class="py-2">Estado</th>
                                <th class="py-2">Orden</th>
                                <th class="py-2 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categorias as $c)
                                <tr class="border-b">
                                    <td class="py-2 font-medium">{{ $c->nombre }}</td>
                                    <td class="py-2 text-gray-600">{{ $c->slug }}</td>
                                    <td class="py-2">
                                        <span class="px-2 py-1 rounded text-sm {{ $c->activo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $c->activo ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </td>
                                    <td class="py-2">{{ $c->orden }}</td>

                                    <td class="py-2 text-right">
                                        <a href="{{ route('categorias.edit', $c) }}"
                                           class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">
                                            Editar
                                        </a>

                                        <form action="{{ route('categorias.destroy', $c) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('¿Eliminar esta categoría?')"
                                                    class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-500">
                                        No hay categorías todavía.
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


