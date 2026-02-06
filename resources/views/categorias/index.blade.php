<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Categorías</h2>
                <p class="app-subtitle">Organizá el catálogo con estados y prioridades claras.</p>
            </div>

            <a href="{{ route('categorias.create') }}" class="app-btn-primary">
                <i class="fas fa-plus"></i>
                Nueva categoría
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="app-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="app-table table-fixed">
                    <thead>
                        <tr>
                            <th class="w-6/12">Nombre</th>
                            <th class="w-3/12">Slug</th>
                            <th class="w-2/12 text-center">Estado</th>
                            <th class="w-1/12 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70">
                        @forelse($categorias as $cat)
                            <tr>
                                <td>
                                    <a class="font-semibold text-slate-900 hover:text-blue-600"
                                       href="{{ route('productos.index', ['categoria_id' => $cat->id]) }}">
                                        {{ $cat->nombre }}
                                    </a>
                                    <div class="mt-1 text-xs text-slate-500">
                                        Ver productos de esta categoría
                                    </div>
                                </td>
                                <td>{{ $cat->slug }}</td>
                                <td class="text-center">
                                    <span class="app-chip {{ ($cat->estado ?? 'activa') === 'activa' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $cat->estado ?? 'activa' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('categorias.edit', $cat) }}" class="app-btn-secondary px-3 py-1.5 text-xs">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('categorias.destroy', $cat) }}" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="app-btn-danger px-3 py-1.5 text-xs">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-sm text-slate-500" colspan="4">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-tags text-2xl text-slate-300"></i>
                                        No hay categorías registradas.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
