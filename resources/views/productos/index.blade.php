<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Productos</h2>
                <p class="text-sm text-slate-500">Gestioná tu catálogo y stock disponible.</p>
            </div>
            <x-button as="a" href="{{ route('productos.create') }}">
                <i class="fa-solid fa-plus"></i>
                Nuevo producto
            </x-button>
        </div>
    </x-slot>

    <div class="space-y-6">
        <x-card title="Filtros" description="Buscá productos por nombre, SKU o categoría.">
            <form method="GET" action="{{ route('productos.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-12">
                <div class="md:col-span-5">
                    <x-input name="q" label="Buscar" :value="$q ?? ''" placeholder="Nombre o SKU..." />
                </div>
                <div class="md:col-span-4">
                    <x-select name="categoria_id" label="Categoría">
                        <option value="">Todas</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id }}" @selected((string)$categoriaId === (string)$c->id)>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                <div class="md:col-span-3 flex items-end gap-2">
                    <x-button type="submit" class="w-full">Buscar</x-button>
                    <x-button variant="outline" as="a" href="{{ route('productos.index') }}" class="w-full">Limpiar</x-button>
                </div>
            </form>
            <p class="mt-3 text-xs text-slate-500">Tip: hacé click en una categoría para ver sus productos automáticamente.</p>
        </x-card>

        <x-table>
            <x-slot name="head">
                <tr>
                    <th class="px-6 py-4">Nombre</th>
                    <th class="px-6 py-4">Categoría</th>
                    <th class="px-6 py-4">SKU</th>
                    <th class="px-6 py-4">Precio</th>
                    <th class="px-6 py-4">Stock</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </x-slot>
            <x-slot name="body">
                @forelse($productos as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $p->nombre }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $p->categoria?->nombre ?? 'Sin categoría' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $p->sku ?? '-' }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-900">
                            $ {{ number_format((float)$p->precio, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <x-badge variant="success">{{ (int)$p->stock }}</x-badge>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <x-button variant="ghost" as="a" href="{{ route('productos.edit', $p) }}">Editar</x-button>
                                <form method="POST" action="{{ route('productos.destroy', $p) }}" onsubmit="return confirm('¿Eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" type="submit">Eliminar</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-10" colspan="6">
                            <x-empty-state title="Sin productos" description="Empezá creando un nuevo producto para mostrarlo acá.">
                                <x-button as="a" href="{{ route('productos.create') }}">Crear producto</x-button>
                            </x-empty-state>
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table>

        <div>
            {{ $productos->links() }}
        </div>
    </div>
</x-app-layout>
