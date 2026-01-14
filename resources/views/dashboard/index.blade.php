<x-app-layout>
    @php
        $ventasFiltradas = max((int) $ventasFiltradas, 0);
        $ticketPromedio = $ventasFiltradas > 0 ? $totalFiltrado / $ventasFiltradas : 0;
        $stockBajoCount = $stockBajo->count();
        $promedioDiario = $ventasPorDia->avg('total') ?? 0;
        $variacionHoy = $promedioDiario > 0 ? (($ingresosHoy - $promedioDiario) / $promedioDiario) * 100 : 0;
        $variacionBadge = $variacionHoy >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700';
        $variacionIcono = $variacionHoy >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down';
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[rgb(var(--brand-600))] text-white shadow-sm">
                        <i class="fas fa-chart-line"></i>
                    </span>
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 leading-tight">
                            Dashboard
                        </h2>
                        <p class="text-sm text-gray-500">Panel de control del kiosko con métricas clave.</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                    <i class="fas fa-signal mr-2"></i>
                    Estado: activo
                </span>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                    Última actualización: {{ now()->format('H:i') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 rounded-2xl border border-slate-100 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 p-6 text-white shadow-xl">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm text-slate-200">Resumen operativo</p>
                            <h3 class="text-2xl font-semibold">Todo listo para vender hoy</h3>
                            <p class="text-sm text-slate-200 mt-2">Controlá ventas, stock y usuarios desde un mismo lugar.</p>
                            <div class="mt-4 grid grid-cols-1 gap-2 text-xs text-slate-200 sm:grid-cols-2">
                                <div class="rounded-lg border border-white/10 bg-white/5 px-3 py-2">
                                    <p class="uppercase tracking-wide text-[10px] text-slate-300">Primera venta</p>
                                    <p class="mt-1 text-sm font-semibold">
                                        {{ $primeraVenta ? $primeraVenta->format('d/m/Y H:i') : 'Sin ventas' }}
                                    </p>
                                </div>
                                <div class="rounded-lg border border-white/10 bg-white/5 px-3 py-2">
                                    <p class="uppercase tracking-wide text-[10px] text-slate-300">Última venta</p>
                                    <p class="mt-1 text-sm font-semibold">
                                        {{ $ultimaVenta ? $ultimaVenta->format('d/m/Y H:i') : 'Sin ventas' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('ventas.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/20">
                                <i class="fas fa-plus-circle"></i>
                                Nueva venta
                            </a>
                            <a href="{{ route('productos.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/20">
                                <i class="fas fa-box-open"></i>
                                Nuevo producto
                            </a>
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="rounded-xl bg-white/10 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-200">Ticket promedio</p>
                            <p class="mt-2 text-2xl font-semibold">$ {{ number_format($ticketPromedio, 2, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl bg-white/10 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-200">Productos con stock bajo</p>
                            <p class="mt-2 text-2xl font-semibold">{{ $stockBajoCount }}</p>
                        </div>
                        <div class="rounded-xl bg-white/10 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-200">Usuarios activos</p>
                            <p class="mt-2 text-2xl font-semibold">{{ $usuarios->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-gray-900">Acciones rápidas</h4>
                        <span class="text-xs text-gray-400">Administración</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('ventas.index') }}" class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-receipt text-blue-600"></i>
                                Revisar ventas
                            </span>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                        </a>
                        <a href="{{ route('categorias.create') }}" class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-indigo-200 hover:bg-indigo-50">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-tags text-indigo-600"></i>
                                Crear categoría
                            </span>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                        </a>
                        <a href="{{ route('productos.index') }}" class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-emerald-200 hover:bg-emerald-50">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-boxes text-emerald-600"></i>
                                Ver inventario
                            </span>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                        </a>
                    </div>
                    <div class="mt-6 rounded-xl bg-slate-50 px-4 py-3 text-xs text-slate-600">
                        Tip: agregá recordatorios de stock mínimo para evitar quiebres de inventario.
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-gray-900">Branding del negocio</h4>
                        <span class="text-xs text-slate-400">Personalización</span>
                    </div>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="flex items-center gap-4 rounded-xl border border-slate-100 p-4">
                            <div class="h-12 w-12 rounded-2xl bg-[rgb(var(--brand-600))] text-white flex items-center justify-center">
                                <i class="fas fa-store"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Tu marca, tu estilo</p>
                                <p class="text-xs text-gray-500">Logo, colores y tipografía alineados.</p>
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-100 p-4">
                            <p class="text-xs text-gray-500">Paleta activa</p>
                            <div class="mt-3 flex items-center gap-2">
                                <span class="h-6 w-6 rounded-full bg-[rgb(var(--brand-600))]"></span>
                                <span class="h-6 w-6 rounded-full bg-blue-200"></span>
                                <span class="h-6 w-6 rounded-full bg-slate-200"></span>
                            </div>
                            <p class="mt-2 text-xs text-gray-400">Actualizá los tonos principales.</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 p-4">
                            <p class="text-xs text-gray-500">Identidad lista</p>
                            <p class="mt-2 text-sm font-semibold text-gray-900">Plantilla social y etiquetas</p>
                            <p class="mt-1 text-xs text-gray-400">Generá banners para campañas rápidas.</p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:border-blue-200 hover:text-blue-600">
                            <i class="fas fa-paint-brush"></i>
                            Configurar branding
                        </a>
                        <button class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-600" type="button">
                            <i class="fas fa-magic"></i>
                            Generar kit visual
                        </button>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-gray-900">Automatización</h4>
                        <span class="text-xs text-slate-400">Workflows</span>
                    </div>
                    <p class="mt-3 text-xs text-gray-500">Activá alertas por stock bajo y reportes automáticos por email/WhatsApp.</p>
                    <div class="mt-4 space-y-2 text-xs text-slate-600">
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 px-3 py-2">
                            <span>Alertas de stock</span>
                            <span class="rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-semibold text-emerald-700">Activo</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 px-3 py-2">
                            <span>Reporte diario</span>
                            <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-600">Pendiente</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 px-3 py-2">
                            <span>Mensajes a clientes</span>
                            <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-600">Pendiente</span>
                        </div>
                    </div>
                    <button class="mt-4 inline-flex items-center gap-2 rounded-xl bg-gray-900 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-800" type="button">
                        <i class="fas fa-bolt"></i>
                        Configurar flujos
                    </button>
                </div>
            </div>

            {{-- KPIs PRINCIPALES (lo primero que se ve) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>Ventas (según filtro)</span>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                            <i class="fas fa-shopping-bag"></i>
                        </span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-gray-900">{{ $ventasFiltradas }}</div>
                    <div class="text-xs text-gray-500 mt-1">Cantidad total</div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>Total $ (según filtro)</span>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                            <i class="fas fa-coins"></i>
                        </span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-gray-900">
                        $ {{ number_format($totalFiltrado, 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Ingresos del período</div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>Ventas hoy</span>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                            <i class="fas fa-calendar-day"></i>
                        </span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-gray-900">{{ $ventasHoy }}</div>
                    <div class="text-xs text-gray-500 mt-1">Solo ventas del día</div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>Ingresos hoy</span>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-sky-50 text-sky-600">
                            <i class="fas fa-wallet"></i>
                        </span>
                    </div>
                    <div class="mt-3 text-3xl font-extrabold text-gray-900">
                        $ {{ number_format($ingresosHoy, 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Solo ingresos del día</div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-gray-900">Insights avanzados</h4>
                        <span class="text-xs text-gray-400">Comparativas</span>
                    </div>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="rounded-xl border border-slate-100 p-4">
                            <p class="text-xs text-gray-500">Promedio diario</p>
                            <p class="mt-2 text-xl font-semibold text-gray-900">$ {{ number_format($promedioDiario, 2, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-1">Basado en período filtrado.</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 p-4">
                            <p class="text-xs text-gray-500">Variación vs promedio</p>
                            <div class="mt-2 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $variacionBadge }}">
                                <i class="fas {{ $variacionIcono }}"></i>
                                {{ number_format($variacionHoy, 1, ',', '.') }}%
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Comparación con ingresos de hoy.</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 p-4">
                            <p class="text-xs text-gray-500">Producto estrella</p>
                            <p class="mt-2 text-sm font-semibold text-gray-900">
                                {{ $topProductos->first()->producto_nombre ?? 'Sin datos aún' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Top ventas del período.</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-gray-900">Seguridad y roles</h4>
                        <span class="text-xs text-slate-400">Checklist</span>
                    </div>
                    <ul class="mt-4 space-y-2 text-xs text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-shield-alt text-emerald-500 mt-0.5"></i>
                            Definí permisos por rol y restricciones de descuentos.
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-clipboard-check text-emerald-500 mt-0.5"></i>
                            Activá auditoría para bajas y anulaciones.
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-user-lock text-emerald-500 mt-0.5"></i>
                            Revisión periódica de usuarios activos.
                        </li>
                    </ul>
                </div>
            </div>

            {{-- FILTROS PLEGABLES (no ocupan pantalla) --}}
            <div x-data="{ open: false }" class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="flex flex-col gap-3 p-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="font-semibold text-gray-900">Filtros inteligentes</div>
                        <div class="text-xs text-gray-500">Ajustá período, turnos o usuario con un clic.</div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('dashboard', ['turno' => 'manana']) }}" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600">Mañana</a>
                        <a href="{{ route('dashboard', ['turno' => 'tarde']) }}" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600">Tarde</a>
                        <a href="{{ route('dashboard', ['turno' => 'noche']) }}" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600">Noche</a>
                    </div>

                    <button type="button"
                            @click="open = !open"
                            class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-3 py-2 text-sm text-white hover:bg-gray-800">
                        <i class="fas fa-sliders-h"></i>
                        <span x-show="!open">Mostrar filtros</span>
                        <span x-show="open" style="display:none;">Ocultar filtros</span>
                    </button>
                </div>

                <div x-show="open" style="display:none;" class="border-t border-gray-100 p-4">
                    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3 items-end">

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">Desde</label>
                            <input type="date" name="desde" value="{{ request('desde') }}"
                                   class="mt-1 w-full rounded-xl border-gray-200">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">Hasta</label>
                            <input type="date" name="hasta" value="{{ request('hasta') }}"
                                   class="mt-1 w-full rounded-xl border-gray-200">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">Mes</label>
                            <input type="month" name="mes" value="{{ request('mes') }}"
                                   class="mt-1 w-full rounded-xl border-gray-200">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">Turno</label>
                            <select name="turno" class="mt-1 w-full rounded-xl border-gray-200">
                                <option value="">Todos</option>
                                <option value="manana" @selected(request('turno')==='manana')>Mañana (06-14)</option>
                                <option value="tarde"  @selected(request('turno')==='tarde')>Tarde (14-22)</option>
                                <option value="noche"  @selected(request('turno')==='noche')>Noche (22-24)</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Hora desde</label>
                            <input type="time" name="hora_desde" value="{{ request('hora_desde') }}"
                                   class="mt-1 w-full rounded-xl border-gray-200">
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Hora hasta</label>
                            <input type="time" name="hora_hasta" value="{{ request('hora_hasta') }}"
                                   class="mt-1 w-full rounded-xl border-gray-200">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">Usuario</label>
                            <select name="user_id" class="mt-1 w-full rounded-xl border-gray-200">
                                <option value="">Todos</option>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-4 flex gap-2">
                            <button class="px-4 py-2 rounded-xl bg-gray-900 text-white hover:bg-gray-800">
                                Aplicar
                            </button>
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200">
                                Limpiar
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            {{-- GRÁFICOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="font-semibold mb-2 text-gray-900">Ventas por día (según filtro)</div>
                    <canvas id="chartVentasDia" height="120"></canvas>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="font-semibold mb-2 text-gray-900">Ventas por método (según filtro)</div>
                    <canvas id="chartMetodo" height="120"></canvas>
                </div>
            </div>

            {{-- STOCK BAJO + TOP PRODUCTOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
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

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
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
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
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
