<x-app-layout>
    @php
        $ventasFiltradas = max((int) $ventasFiltradas, 0);
    @endphp

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Panel de ventas</h2>
                <p class="app-subtitle">Vista operativa para el equipo de ventas.</p>
            </div>
        </div>
    </x-slot>

    <div class="app-page space-y-6">
        <div x-data="{ open: false }" class="app-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Filtros de período</h3>
                    <p class="text-xs text-slate-500">Filtrá por fechas, turno u horas para revisar las ventas del período.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="app-chip bg-slate-100 text-slate-600">
                        <i class="fas fa-filter mr-2"></i>
                        Filtros activos: {{ $desde || $hasta || $turno || $horaDesde || $horaHasta ? 'Sí' : 'No' }}
                    </span>
                    <button type="button" @click="open = !open" class="app-btn-ghost border border-slate-200">
                        <i class="fas fa-sliders"></i>
                        <span x-text="open ? 'Ocultar filtros' : 'Mostrar filtros'"></span>
                    </button>
                </div>
            </div>

            <form x-show="open" x-transition class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6" method="GET" action="{{ route('dashboard') }}">
                <label class="text-xs font-semibold text-slate-500">
                    Mes (prioridad)
                    <input class="app-input mt-1" type="month" name="mes" value="{{ request('mes') }}">
                </label>

                <label class="text-xs font-semibold text-slate-500">
                    Desde
                    <input class="app-input mt-1" type="date" name="desde" value="{{ $desde?->format('Y-m-d') }}">
                </label>

                <label class="text-xs font-semibold text-slate-500">
                    Hasta
                    <input class="app-input mt-1" type="date" name="hasta" value="{{ $hasta?->format('Y-m-d') }}">
                </label>

                <label class="text-xs font-semibold text-slate-500">
                    Turno
                    <select class="app-input mt-1" name="turno">
                        <option value="">Todos</option>
                        <option value="manana" @selected($turno === 'manana')>Mañana (06:00 - 13:59)</option>
                        <option value="tarde" @selected($turno === 'tarde')>Tarde (14:00 - 21:59)</option>
                        <option value="noche" @selected($turno === 'noche')>Noche (22:00 - 23:59)</option>
                    </select>
                </label>

                <label class="text-xs font-semibold text-slate-500">
                    Hora desde
                    <input class="app-input mt-1" type="time" name="hora_desde" value="{{ $horaDesde }}">
                </label>

                <label class="text-xs font-semibold text-slate-500">
                    Hora hasta
                    <input class="app-input mt-1" type="time" name="hora_hasta" value="{{ $horaHasta }}">
                </label>

                <div class="flex items-end gap-2 md:col-span-2 lg:col-span-1 xl:col-span-2">
                    <button class="app-btn-primary w-full" type="submit">
                        <i class="fas fa-magnifying-glass-chart"></i>
                        Aplicar
                    </button>
                    <a href="{{ route('dashboard') }}" class="app-btn-secondary w-full">
                        <i class="fas fa-rotate-left"></i>
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="app-card p-5 text-center">
                <div class="flex flex-col items-center gap-2 text-sm text-slate-500">
                    <i class="fas fa-bolt text-xl text-emerald-600"></i>
                    <span>Ventas hoy</span>
                </div>
                <div class="mt-3 text-4xl font-semibold text-slate-900">{{ $ventasHoy }}</div>
                <div class="mt-1 text-xs text-slate-500">Ingresos $ {{ number_format($ingresosHoy, 2, ',', '.') }}</div>
            </div>

            <div class="app-card p-5 text-center">
                <div class="flex flex-col items-center gap-2 text-sm text-slate-500">
                    <i class="fas fa-calendar-check text-xl text-sky-600"></i>
                    <span>Ventas del período</span>
                </div>
                <div class="mt-3 text-4xl font-semibold text-slate-900">{{ $ventasMes }}</div>
                <div class="mt-1 text-xs text-slate-500">Ingresos $ {{ number_format($ingresosMes, 2, ',', '.') }}</div>
            </div>

            <div class="app-card p-5 text-center">
                <div class="flex flex-col items-center gap-2 text-sm text-slate-500">
                    <i class="fas fa-receipt text-xl text-amber-600"></i>
                    <span>Promedio diario</span>
                </div>
                <div class="mt-3 text-3xl font-semibold text-slate-900">$ {{ number_format($promedioDiario, 2, ',', '.') }}</div>
                <div class="mt-1 text-xs text-slate-500">Ingreso promedio por día en el período filtrado</div>
            </div>

            <div class="app-card p-5 text-center">
                <div class="flex flex-col items-center gap-2 text-sm text-slate-500">
                    <i class="fas fa-coins text-xl text-purple-600"></i>
                    <span>Total vendido del período</span>
                </div>
                <div class="mt-3 text-3xl font-semibold text-slate-900">$ {{ number_format($totalFiltrado, 2, ',', '.') }}</div>
                <div class="mt-1 text-xs text-slate-500">Monto acumulado en el período</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="rounded-[28px] bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 p-6 text-white shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Ventas por día</h3>
                        <p class="text-xs text-slate-200">Tendencia de operaciones del período.</p>
                    </div>
                    <div class="app-chip bg-white/10 text-slate-100">Tendencia</div>
                </div>
                <div class="mt-4 h-56 rounded-2xl bg-white/95 p-4 text-slate-900">
                    <canvas id="chartVentasDia" height="180"></canvas>
                </div>
            </div>

            <div class="rounded-[28px] bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 p-6 text-white shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Métodos de pago</h3>
                        <p class="text-xs text-slate-200">Cómo prefieren pagar tus clientes.</p>
                    </div>
                    <div class="app-chip bg-white/10 text-slate-100">Resumen</div>
                </div>
                <div class="mt-4 h-56 rounded-2xl bg-white/95 p-4 text-slate-900">
                    <canvas id="chartMetodo" height="180"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="app-card p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-semibold text-slate-900">Top productos vendidos</div>
                    <div class="text-xs text-slate-500">Según tu período</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-right">Unidades</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70">
                            @forelse($topProductos as $row)
                                <tr>
                                    <td class="font-semibold text-slate-900">
                                        {{ $row->producto_nombre ?? 'Producto eliminado' }}
                                    </td>
                                    <td class="text-right font-semibold">
                                        {{ $row->total_vendido ?? 0 }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-sm text-slate-500">No hay ventas en este período.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="app-card p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-semibold text-slate-900">Stock bajo</div>
                    <div class="text-xs text-slate-500">Productos con reposición urgente</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th class="text-right">Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70">
                            @forelse($stockBajo as $producto)
                                <tr>
                                    <td class="font-semibold text-slate-900">{{ $producto->nombre }}</td>
                                    <td class="text-sm text-slate-500">{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</td>
                                    <td class="text-right font-semibold text-rose-600">{{ $producto->stock }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-sm text-slate-500">Sin alertas de stock por ahora.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ventasDiaLabels = @json($ventasPorDia->pluck('fecha'));
        const ventasDiaCant   = @json($ventasPorDia->pluck('cantidad'));
        const ventasDiaTotal  = @json($ventasPorDia->pluck('total'));
        const metodoLabels    = @json($ventasPorMetodo->pluck('metodo_pago'));
        const metodoCant      = @json($ventasPorMetodo->pluck('cantidad'));

        const baseGrid = {
            color: 'rgba(148, 163, 184, 0.2)'
        };

        new Chart(document.getElementById('chartVentasDia'), {
            type: 'line',
            data: {
                labels: ventasDiaLabels,
                datasets: [
                    {
                        label: 'Cantidad',
                        data: ventasDiaCant,
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Total ($)',
                        data: ventasDiaTotal,
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        tension: 0.3,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: baseGrid },
                    y: { grid: baseGrid }
                }
            }
        });

        new Chart(document.getElementById('chartMetodo'), {
            type: 'bar',
            data: {
                labels: metodoLabels,
                datasets: [
                    {
                        label: 'Cantidad',
                        data: metodoCant,
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: baseGrid },
                    y: { grid: baseGrid }
                }
            }
        });
    </script>
</x-app-layout>
