<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Ventas</h2>
                <p class="app-subtitle">Indicadores clave y control de filtros para el período seleccionado.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('ventas.cierre') }}" class="app-btn-secondary">
                    <i class="fas fa-cash-register"></i>
                    Cierre de caja
                </a>
                <a href="{{ route('ventas.create') }}" class="app-btn-primary">
                    <i class="fas fa-plus"></i>
                    Nueva venta
                </a>
            </div>
        </div>
    </x-slot>

    <div class="app-page space-y-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-blue-50 text-blue-700">Cantidad de ventas</span>
                    <span class="text-xs font-semibold text-blue-600">Total</span>
                </div>
                <div class="mt-4 text-4xl font-semibold text-slate-900">{{ (int)$cantidadVentas }}</div>
                <p class="mt-2 text-sm text-slate-500">Conteo total según los filtros aplicados.</p>
            </div>
            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-emerald-50 text-emerald-700">Total en dinero</span>
                    <span class="text-xs font-semibold text-emerald-600">Acumulado</span>
                </div>
                <div class="mt-4 text-4xl font-semibold text-slate-900">
                    $ {{ number_format((float)$totalDinero, 2, ',', '.') }}
                </div>
                <p class="mt-2 text-sm text-slate-500">Suma total del período filtrado.</p>
            </div>
            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-amber-50 text-amber-700">Promedio</span>
                    <span class="text-xs font-semibold text-amber-600">Promedio</span>
                </div>
                <div class="mt-4 text-4xl font-semibold text-slate-900">
                    {{ number_format((float) $diasPromedio, 0, ',', '.') }} días
                </div>
                <p class="mt-2 text-sm text-slate-500">Promedio de días en el período filtrado.</p>
            </div>
        </div>

        <div x-data="{ open: {{ $desde || $hasta || $buscar ? 'true' : 'false' }} }" class="app-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Filtros de ventas</h3>
                    <p class="text-xs text-slate-500">Definí fechas o buscá por ID, método, estado o usuario.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="app-chip bg-slate-100 text-slate-600">
                        <i class="fas fa-filter mr-2"></i>
                        Filtros activos: {{ $desde || $hasta || $buscar ? 'Sí' : 'No' }}
                    </span>
                    <button type="button" @click="open = !open" class="app-btn-ghost border border-slate-200">
                        <i class="fas fa-sliders"></i>
                        <span x-text="open ? 'Ocultar filtros' : 'Mostrar filtros'"></span>
                    </button>
                </div>
            </div>

            <form x-show="open" x-transition class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6" method="GET" action="{{ route('ventas.index') }}">
                <label class="text-xs font-semibold text-slate-500">
                    Desde
                    <input class="app-input mt-1" type="date" name="desde" value="{{ $desde }}">
                </label>

                <label class="text-xs font-semibold text-slate-500">
                    Hasta
                    <input class="app-input mt-1" type="date" name="hasta" value="{{ $hasta }}">
                </label>

                <label class="text-xs font-semibold text-slate-500 md:col-span-2 xl:col-span-3">
                    Buscar
                    <input class="app-input mt-1" name="q" value="{{ $buscar }}" placeholder="ID, método, estado o usuario...">
                </label>

                <div class="flex items-end gap-2 md:col-span-2 xl:col-span-1">
                    <button class="app-btn-primary w-full" type="submit">
                        <i class="fas fa-magnifying-glass-chart"></i>
                        Aplicar
                    </button>
                    <a href="{{ route('ventas.index') }}" class="app-btn-secondary w-full">
                        <i class="fas fa-rotate-left"></i>
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <div class="app-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70">
                        @forelse($ventas as $v)
                            <tr>
                                <td class="font-semibold text-slate-900">#{{ $v->id }}</td>
                                <td>{{ $v->created_at?->format('d/m/Y H:i') }}</td>
                                <td>{{ $v->usuario?->name ?? '-' }}</td>
                                <td>{{ $v->metodo_pago }}</td>
                                <td>
                                    <span class="app-chip bg-emerald-50 text-emerald-700">
                                        {{ $v->estado }}
                                    </span>
                                </td>
                                <td class="font-semibold text-slate-900">
                                    $ {{ number_format((float)$v->total, 2, ',', '.') }}
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('ventas.show', $v) }}" class="app-btn-secondary px-3 py-1.5 text-xs">
                                            Ver
                                        </a>
                                        @if ($esAdmin || $v->user_id === auth()->id())
                                            <a href="{{ route('ventas.edit', $v) }}" class="app-btn-secondary px-3 py-1.5 text-xs">
                                                Editar
                                            </a>
                                            <form method="POST" action="{{ route('ventas.destroy', $v) }}" onsubmit="return confirm('¿Eliminar esta venta?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="app-btn-danger px-3 py-1.5 text-xs">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-sm text-slate-500" colspan="7">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-receipt text-2xl text-slate-300"></i>
                                        No hay ventas para mostrar.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200/70 p-4">
                {{ $ventas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
