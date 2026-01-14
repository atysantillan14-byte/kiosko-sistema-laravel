<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Ventas</h2>
                <p class="text-sm text-slate-500">Seguimiento de ventas y estados de cobro.</p>
            </div>
            <x-button as="a" href="{{ route('ventas.create') }}">
                <i class="fa-solid fa-plus"></i>
                Nueva venta
            </x-button>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <x-card>
                <p class="text-sm font-semibold text-slate-600">Cantidad de ventas</p>
                <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ (int)$cantidadVentas }}</p>
                <p class="text-xs text-slate-500 mt-1">Según filtros aplicados</p>
            </x-card>
            <x-card>
                <p class="text-sm font-semibold text-slate-600">Total en dinero</p>
                <p class="mt-2 text-3xl font-extrabold text-slate-900">
                    $ {{ number_format((float)$totalDinero, 2, ',', '.') }}
                </p>
                <p class="text-xs text-slate-500 mt-1">Según filtros aplicados</p>
            </x-card>
        </div>

        <x-card title="Filtros" description="Ajustá rangos por fecha o búsqueda rápida.">
            <form method="GET" action="{{ route('ventas.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-12">
                <div class="md:col-span-3">
                    <x-input name="desde" type="date" label="Desde" :value="$desde" />
                </div>
                <div class="md:col-span-3">
                    <x-input name="hasta" type="date" label="Hasta" :value="$hasta" />
                </div>
                <div class="md:col-span-4">
                    <x-input name="q" label="Buscar" :value="$buscar" placeholder="ID / método / estado / usuario..." />
                </div>
                <div class="md:col-span-2 flex items-end gap-2">
                    <x-button type="submit" class="w-full">Aplicar</x-button>
                    <x-button variant="outline" as="a" href="{{ route('ventas.index') }}" class="w-full">Limpiar</x-button>
                </div>
            </form>
        </x-card>

        <x-table>
            <x-slot name="head">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Fecha</th>
                    <th class="px-6 py-4">Usuario</th>
                    <th class="px-6 py-4">Método</th>
                    <th class="px-6 py-4">Estado</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </x-slot>
            <x-slot name="body">
                @forelse($ventas as $v)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-semibold text-slate-900">#{{ $v->id }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $v->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $v->usuario?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $v->metodo_pago }}</td>
                        <td class="px-6 py-4">
                            <x-badge variant="success">{{ $v->estado }}</x-badge>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-900">
                            $ {{ number_format((float)$v->total, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <x-button variant="ghost" as="a" href="{{ route('ventas.show', $v) }}">Ver</x-button>
                                <x-button variant="ghost" as="a" href="{{ route('ventas.edit', $v) }}">Editar</x-button>
                                <form method="POST" action="{{ route('ventas.destroy', $v) }}" onsubmit="return confirm('¿Eliminar esta venta?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" type="submit">Eliminar</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-10" colspan="7">
                            <x-empty-state title="Sin ventas" description="Generá una venta para visualizarla aquí.">
                                <x-button as="a" href="{{ route('ventas.create') }}">Registrar venta</x-button>
                            </x-empty-state>
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table>

        <div>
            {{ $ventas->links() }}
        </div>
    </div>
</x-app-layout>
