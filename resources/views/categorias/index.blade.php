<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Categorías</h2>
                <p class="text-sm text-slate-500">Organizá tu catálogo por familias.</p>
            </div>
            <x-button as="a" href="{{ route('categorias.create') }}">
                <i class="fa-solid fa-plus"></i>
                Nueva categoría
            </x-button>
        </div>
    </x-slot>

    <x-table>
        <x-slot name="head">
            <tr>
                <th class="px-6 py-4">Nombre</th>
                <th class="px-6 py-4">Slug</th>
                <th class="px-6 py-4">Estado</th>
                <th class="px-6 py-4">Orden</th>
                <th class="px-6 py-4 text-right">Acciones</th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @foreach($categorias as $cat)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <a class="font-semibold text-slate-900 hover:text-brand-600" href="{{ route('productos.index', ['categoria_id' => $cat->id]) }}">
                            {{ $cat->nombre }}
                        </a>
                        <div class="text-xs text-slate-500 mt-1">Ver productos de esta categoría</div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $cat->slug }}</td>
                    <td class="px-6 py-4">
                        <x-badge :variant="($cat->estado ?? 'activa') === 'activa' ? 'success' : 'neutral'">
                            {{ $cat->estado ?? 'activa' }}
                        </x-badge>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $cat->orden ?? 0 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <x-button variant="ghost" as="a" href="{{ route('categorias.edit', $cat) }}">Editar</x-button>
                            <form method="POST" action="{{ route('categorias.destroy', $cat) }}" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                @csrf
                                @method('DELETE')
                                <x-button variant="danger" type="submit">Eliminar</x-button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-table>
</x-app-layout>
