<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
            <div class="text-xs text-gray-500">
                Panel de control del kiosko
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- KPIs PRINCIPALES (lo primero que se ve) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="text-sm text-gray-500">Ventas (según filtro)</div>
                    <div class="text-3xl font-extrabold text-gray-900">{{ $ventasFiltradas }}</div>
                    <div class="text-xs text-gray-500 mt-1">Cantidad total</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="text-sm text-gray-500">Total $ (según filtro)</div>
                    <div class="text-3xl font-extrabold text-gray-900">
                        $ {{ number_format($totalFiltrado, 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Ingresos del período</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="text-sm text-gray-500">Ventas hoy (modo filtro)</div>
                    <div class="text-3xl font-extrabold text-gray-900">{{ $ventasHoy }}</div>
                    <div class="text-xs text-gray-500 mt-1">Refleja el filtro actual</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="text-sm text-gray-500">Ingresos hoy (modo filtro)</div>
                    <div class="text-3xl font-extrabold text-gray-900">
                        $ {{ number_format($ingresosHoy, 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Refleja el filtro actual</div>
                </div>
            </div>

            {{-- FILTROS PLEGABLES (no ocupan pantalla) --}}
            <div x-data="{ open: false }" class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <div class="font-semibold text-gray-900">Filtros</div>
                        <div class="text-xs text-gray-500">Opcional: ajustá período / turnos / usuario</div>
                    </div>

                    <button type="button"
                            @click="open = !open"
                            class="px-3 py-2 rounded-md bg-gray-900 text-white text-sm hover:bg-gray-800">
                        <span x-show="!open">Mostrar</span>
                        <span x-show="open" style="display:none;">Ocultar</span>
                    </button>
                </div>

                <div x-show="open" style="display:none;" class="border-t border-gray-100 p-4">
                    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3 items-end">

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">Desde</label>
                            <input type="date" name="desde" value="{{ request('desde') }}"
                                   class="mt-1 w-full rounded-md border-gray-300">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">Hasta</label>
                            <input type="date" name="hasta" value="{{ request('hasta') }}"
                                   class="mt-1 w-full rounded-md border-gray-300">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">Mes</label>
                            <input type="month" name="mes" value="{{ request('mes') }}"
                                   class="mt-1 w-full rounded-md border-gray-300">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">Turno</label>
                            <select name="turno" class="mt-1 w-full rounded-md border-gray-300">
                                <option value="">Todos</option>
                                <option value="manana" @selected(request('turno')==='manana')>Mañana (06-14)</option>
                                <option value="tarde"  @selected(request('turno')==='tarde')>Tarde (14-22)</option>
                                <option value="noche"  @selected(request('turno')==='noche')>Noche (22-24)</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Hora desde</label>
                            <input type="time" name="hora_desde" value="{{ request('hora_desde') }}"
                                   class="mt-1 w-full rounded-md border-gray-300">
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Hora hasta</label>
                            <input type="time" name="hora_hasta" value="{{ request('hora_hasta') }}"
                                   class="mt-1 w-full rounded-md border-gray-300">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">Usuario</label>
                            <select name="user_id" class="mt-1 w-full rounded-md border-gray-300">
                                <option value="">Todos</option>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-4 flex gap-2">
                            <button class="px-4 py-2 rounded-md bg-gray-900 text-white hover:bg-gray-800">
                                Aplicar
                            </button>
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200">
                                Limpiar
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            {{-- GRÁFICOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="font-semibold mb-2 text-gray-900">Ventas por día (según filtro)</div>
                    <canvas id="chartVentasDia" height="120"></canvas>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="font-semibold mb-2 text-gray-900">Ventas por método (según filtro)</div>
                    <canvas id="chartMetodo" height="120"></canvas>
                </div>
            </div>

            {{-- STOCK BAJO + TOP PRODUCTOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-gray-900">Stock bajo (≤ 5)</div>
                        <div class="text-xs text-gray-500">Top 10</div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="py-2">Producto</th>
                                    <th class="py-2">Categoría</th>
                                    <th class="py-2 text-right">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse($stockBajo as $p)
                                    <tr>
                                        <td class="py-2 font-medium text-gray-900">{{ $p->nombre }}</td>
                                        <td class="py-2 text-gray-600">{{ optional($p->categoria)->nombre ?? '-' }}</td>
                                        <td class="py-2 text-right">
                                            <span class="inline-flex px-2 py-1 rounded-lg bg-red-50 text-red-700 text-xs font-semibold">
                                                {{ $p->stock }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-3 text-gray-500">No hay productos con stock bajo.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-gray-900">Top productos vendidos</div>
                        <div class="text-xs text-gray-500">Según filtro</div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="py-2">Producto</th>
                                    <th class="py-2 text-right">Unidades</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse($topProductos as $row)
                                    <tr>
                                        <td class="py-2 font-medium text-gray-900">
                                            {{ $row->producto_nombre ?? 'Producto eliminado' }}
                                        </td>
                                        <td class="py-2 text-right font-semibold">
                                            {{ $row->total_vendido ?? 0 }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="py-3 text-gray-500">No hay ventas en este período.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- RANKING USUARIOS (SOLO ADMIN) --}}
            @if($esAdmin)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-gray-900">Ranking de usuarios</div>
                        <div class="text-xs text-gray-500">Solo administrador</div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="py-2">Usuario</th>
                                    <th class="py-2 text-right">Ventas</th>
                                    <th class="py-2 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse($rankingUsuarios as $r)
                                    <tr>
                                        <td class="py-2 font-medium text-gray-900">{{ optional($r->usuario)->name ?? 'Usuario' }}</td>
                                        <td class="py-2 text-right font-semibold">{{ $r->cantidad }}</td>
                                        <td class="py-2 text-right font-semibold">$ {{ number_format($r->total, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-3 text-gray-500">Sin datos para este período.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ventasDiaLabels = @json($ventasPorDia->pluck('fecha'));
        const ventasDiaCant   = @json($ventasPorDia->pluck('cantidad'));
        const ventasDiaTotal  = @json($ventasPorDia->pluck('total'));

        new Chart(document.getElementById('chartVentasDia'), {
            type: 'line',
            data: {
                labels: ventasDiaLabels,
                datasets: [
                    { label: 'Cantidad de ventas', data: ventasDiaCant, tension: 0.3 },
                    { label: 'Total ($)', data: ventasDiaTotal, tension: 0.3 }
                ]
            }
        });

        const metodoLabels = @json($ventasPorMetodo->pluck('metodo_pago'));
        const metodoCant   = @json($ventasPorMetodo->pluck('cantidad'));

        new Chart(document.getElementById('chartMetodo'), {
            type: 'bar',
            data: {
                labels: metodoLabels,
                datasets: [
                    { label: 'Cantidad', data: metodoCant }
                ]
            }
        });
    </script>
</x-app-layout>
