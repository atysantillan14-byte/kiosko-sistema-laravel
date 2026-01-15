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
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">
                    Dashboard
                </h2>
                <p class="text-sm text-slate-500">Panel de control del kiosko con métricas clave.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section class="rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 p-8 shadow-2xl">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[11px] font-semibold text-slate-200">
                            <i class="fas fa-sparkles"></i>
                            Panel de gestión
                        </span>
                        <h3 class="mt-4 text-3xl font-semibold text-white">Bienvenido al centro operativo del kiosko</h3>
                        <p class="mt-3 text-sm text-slate-300">
                            Accesos directos a las áreas más importantes con un diseño moderno, claro y profesional.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('ventas.create') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/20">
                            <i class="fas fa-plus-circle"></i>
                            Registrar venta
                        </a>
                        <a href="{{ route('productos.create') }}" class="inline-flex items-center gap-2 rounded-full border border-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                            <i class="fas fa-box-open"></i>
                            Cargar producto
                        </a>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
                    <a href="{{ route('ventas.index') }}" class="group rounded-2xl border border-white/10 bg-white/5 p-5 text-white transition hover:-translate-y-1 hover:bg-white/10">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-rose-500/20 text-rose-200">
                                <i class="fas fa-receipt"></i>
                            </span>
                            <i class="fas fa-arrow-right text-xs text-white/60 transition group-hover:translate-x-1"></i>
                        </div>
                        <h4 class="mt-4 text-base font-semibold">Ventas</h4>
                        <p class="mt-1 text-xs text-slate-300">Registrar operaciones y ver historiales.</p>
                    </a>

                    <a href="{{ route('productos.index') }}" class="group rounded-2xl border border-white/10 bg-white/5 p-5 text-white transition hover:-translate-y-1 hover:bg-white/10">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-200">
                                <i class="fas fa-boxes-stacked"></i>
                            </span>
                            <i class="fas fa-arrow-right text-xs text-white/60 transition group-hover:translate-x-1"></i>
                        </div>
                        <h4 class="mt-4 text-base font-semibold">Productos</h4>
                        <p class="mt-1 text-xs text-slate-300">Inventario, stock y precios.</p>
                    </a>

                    <a href="{{ route('categorias.index') }}" class="group rounded-2xl border border-white/10 bg-white/5 p-5 text-white transition hover:-translate-y-1 hover:bg-white/10">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-amber-500/20 text-amber-200">
                                <i class="fas fa-tags"></i>
                            </span>
                            <i class="fas fa-arrow-right text-xs text-white/60 transition group-hover:translate-x-1"></i>
                        </div>
                        <h4 class="mt-4 text-base font-semibold">Categorías</h4>
                        <p class="mt-1 text-xs text-slate-300">Ordená tu catálogo por familias.</p>
                    </a>

                    <a href="{{ route('dashboard') }}" class="group rounded-2xl border border-white/10 bg-white/5 p-5 text-white transition hover:-translate-y-1 hover:bg-white/10">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-sky-500/20 text-sky-200">
                                <i class="fas fa-chart-line"></i>
                            </span>
                            <i class="fas fa-arrow-right text-xs text-white/60 transition group-hover:translate-x-1"></i>
                        </div>
                        <h4 class="mt-4 text-base font-semibold">Estadísticas</h4>
                        <p class="mt-1 text-xs text-slate-300">Indicadores y comparativas diarias.</p>
                    </a>

                    <a href="{{ route('proveedores.index') }}" class="group rounded-2xl border border-white/10 bg-white/5 p-5 text-white transition hover:-translate-y-1 hover:bg-white/10">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-violet-500/20 text-violet-200">
                                <i class="fas fa-truck"></i>
                            </span>
                            <i class="fas fa-arrow-right text-xs text-white/60 transition group-hover:translate-x-1"></i>
                        </div>
                        <h4 class="mt-4 text-base font-semibold">Proveedores</h4>
                        <p class="mt-1 text-xs text-slate-300">Abastecimiento y acuerdos clave.</p>
                    </a>
                </div>
            </section>

            <div x-data="{ open: false }"
                 class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Filtros del dashboard</h3>
                        <p class="text-xs text-gray-500">Personalizá el período, turno y usuario para refinar los indicadores.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-600">
                            <i class="fas fa-filter mr-2"></i>
                            Filtros activos: {{ $desde || $hasta || $turno || $horaDesde || $horaHasta || $userId ? 'Sí' : 'No' }}
                        </span>
                        <button type="button"
                                @click="open = !open"
                                class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-[11px] font-semibold text-slate-600 hover:bg-slate-50">
                            <i class="fas fa-sliders"></i>
                            <span x-text="open ? 'Ocultar filtros' : 'Mostrar filtros'">Mostrar filtros</span>
                        </button>
                    </div>
                </div>

                <form x-show="open"
                      x-transition
                      class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6"
                      method="GET"
                      action="{{ route('dashboard') }}">
                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500">
                        Mes (prioridad)
                        <input
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            type="month"
                            name="mes"
                            value="{{ request('mes') }}"
                        >
                    </label>

                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500">
                        Desde
                        <input
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            type="date"
                            name="desde"
                            value="{{ $desde?->format('Y-m-d') }}"
                        >
                    </label>

                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500">
                        Hasta
                        <input
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            type="date"
                            name="hasta"
                            value="{{ $hasta?->format('Y-m-d') }}"
                        >
                    </label>

                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500">
                        Turno
                        <select
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            name="turno"
                        >
                            <option value="">Todos</option>
                            <option value="manana" @selected($turno === 'manana')>Mañana (06:00 - 13:59)</option>
                            <option value="tarde" @selected($turno === 'tarde')>Tarde (14:00 - 21:59)</option>
                            <option value="noche" @selected($turno === 'noche')>Noche (22:00 - 23:59)</option>
                        </select>
                    </label>

                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500">
                        Hora desde
                        <input
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            type="time"
                            name="hora_desde"
                            value="{{ $horaDesde }}"
                        >
                    </label>

                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500">
                        Hora hasta
                        <input
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            type="time"
                            name="hora_hasta"
                            value="{{ $horaHasta }}"
                        >
                    </label>

                    <label class="flex flex-col gap-1 text-xs font-semibold text-gray-500 md:col-span-2 lg:col-span-2 xl:col-span-2">
                        Usuario
                        <select
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-gray-700 focus:border-slate-300 focus:ring-2 focus:ring-slate-200"
                            name="user_id"
                        >
                            <option value="">Todos</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" @selected((int) $userId === $usuario->id)>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <div class="flex items-end gap-2 md:col-span-2 lg:col-span-1 xl:col-span-2">
                        <button class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-800" type="submit">
                            <i class="fas fa-magnifying-glass-chart"></i>
                            Aplicar
                        </button>
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            <i class="fas fa-rotate-left"></i>
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- GRÁFICOS PRINCIPALES (primera vista) --}}
            <div class="rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 p-6 shadow-xl">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-white">Visualización general</h3>
                        <p class="text-sm text-slate-300">Resumen moderno con métricas clave y comportamiento de ventas.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-slate-100">
                        <i class="fas fa-chart-pie"></i>
                        Panel analítico
                    </div>
                </div>
                <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl bg-white/95 p-4 text-slate-900 shadow-sm ring-1 ring-white/10">
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                            <span>Ventas por día</span>
                            <i class="fas fa-wave-square text-slate-400"></i>
                        </div>
                        <div class="mt-3">
                            <canvas id="chartVentasDia" height="140"></canvas>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white/95 p-4 text-slate-900 shadow-sm ring-1 ring-white/10">
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                            <span>Ventas por método</span>
                            <i class="fas fa-layer-group text-slate-400"></i>
                        </div>
                        <div class="mt-3">
                            <canvas id="chartMetodo" height="140"></canvas>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white/95 p-4 text-slate-900 shadow-sm ring-1 ring-white/10">
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                            <span>Participación por método</span>
                            <i class="fas fa-chart-pie text-slate-400"></i>
                        </div>
                        <div class="mt-3">
                            <canvas id="chartMetodoPie" height="140"></canvas>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white/95 p-4 text-slate-900 shadow-sm ring-1 ring-white/10">
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                            <span>Stock por producto</span>
                            <i class="fas fa-boxes-stacked text-slate-400"></i>
                        </div>
                        <div class="mt-3">
                            <canvas id="chartStockProducto" height="140"></canvas>
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-xs text-slate-300">
                    Visualización optimizada para detectar tendencias y posibles quiebres de inventario.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-slate-100 bg-white p-5 text-center shadow-sm">
                    <div class="flex flex-col items-center gap-2 text-sm text-gray-500">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                            <i class="fas fa-boxes"></i>
                        </span>
                        <span>Productos registrados</span>
                    </div>
                    <div class="mt-3 text-4xl font-black tracking-tight text-gray-900">{{ $productosCount }}</div>
                    <div class="mt-1 text-xs text-gray-500">Inventario total activo</div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 text-center shadow-sm">
                    <div class="flex flex-col items-center gap-2 text-sm text-gray-500">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                            <i class="fas fa-flag-checkered"></i>
                        </span>
                        <span>Primera venta registrada</span>
                    </div>
                    <div class="mt-3 text-2xl font-extrabold tracking-tight text-gray-900">
                        {{ $primeraVenta ? $primeraVenta->format('d/m/Y H:i') : 'Sin ventas' }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">Fecha de inicio de actividad</div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 text-center shadow-sm">
                    <div class="flex flex-col items-center gap-2 text-sm text-gray-500">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-sky-50 text-sky-600">
                            <i class="fas fa-clock"></i>
                        </span>
                        <span>Última venta registrada</span>
                    </div>
                    <div class="mt-3 text-2xl font-extrabold tracking-tight text-gray-900">
                        {{ $ultimaVenta ? $ultimaVenta->format('d/m/Y H:i') : 'Sin ventas' }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">Última actualización real</div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div class="rounded-2xl border border-slate-100 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 p-6 text-white shadow-xl">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm text-slate-200">Resumen operativo</p>
                            <h3 class="text-2xl font-semibold">Todo listo para vender hoy</h3>
                            <p class="text-sm text-slate-200 mt-2">Controlá ventas, stock y usuarios desde un mismo lugar.</p>
                            <div class="mt-4 grid grid-cols-2 gap-2 text-xs text-slate-200">
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
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-gray-900">Automatización</h4>
                        <span class="text-xs text-slate-400">Workflows</span>
                    </div>
                    <p class="mt-3 text-xs text-gray-500">Alertas y reportes diarios activos con envío por WhatsApp al administrador.</p>
                    <div class="mt-4 space-y-2 text-xs text-slate-600">
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 px-3 py-2">
                            <span>Alertas de stock (WhatsApp admin)</span>
                            <span class="rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-semibold text-emerald-700">Activo</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 px-3 py-2">
                            <span>Reporte diario (WhatsApp admin)</span>
                            <span class="rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-semibold text-emerald-700">Activo</span>
                        </div>
                    </div>
                    <button class="mt-4 inline-flex items-center gap-2 rounded-xl bg-gray-900 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-800" type="button">
                        <i class="fas fa-bolt"></i>
                        Configurar alertas
                    </button>
                </div>
            </div>

            {{-- KPIs PRINCIPALES --}}
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

            <div class="grid grid-cols-1 gap-4">
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
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
        const metodoLabels    = @json($ventasPorMetodo->pluck('metodo_pago'));
        const metodoCant      = @json($ventasPorMetodo->pluck('cantidad'));
        const stockLabels     = @json($stockPorProducto->pluck('nombre'));
        const stockCant       = @json($stockPorProducto->pluck('stock'));

        const baseGrid = {
            color: 'rgba(148, 163, 184, 0.2)'
        };

        new Chart(document.getElementById('chartVentasDia'), {
            type: 'line',
            data: {
                labels: ventasDiaLabels,
                datasets: [
                    {
                        label: 'Cantidad de ventas',
                        data: ventasDiaCant,
                        tension: 0.35,
                        borderColor: 'rgba(37, 99, 235, 0.9)',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true
                    },
                    {
                        label: 'Total ($)',
                        data: ventasDiaTotal,
                        tension: 0.35,
                        borderColor: 'rgba(16, 185, 129, 0.9)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#0f172a',
                            boxWidth: 10
                        }
                    }
                },
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
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { grid: baseGrid },
                    y: { grid: baseGrid }
                }
            }
        });

        const metodoTotal = metodoCant.reduce((acc, value) => acc + value, 0);

        new Chart(document.getElementById('chartMetodoPie'), {
            type: 'doughnut',
            data: {
                labels: metodoLabels,
                datasets: [
                    {
                        data: metodoCant,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(234, 179, 8, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(99, 102, 241, 0.8)'
                        ],
                        borderWidth: 0
                    }
                ]
            },
            options: {
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#0f172a',
                            boxWidth: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const value = context.raw || 0;
                                const percentage = metodoTotal ? ((value / metodoTotal) * 100).toFixed(1) : 0;
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('chartStockProducto'), {
            type: 'bar',
            data: {
                labels: stockLabels,
                datasets: [
                    {
                        label: 'Stock',
                        data: stockCant,
                        backgroundColor: 'rgba(15, 118, 110, 0.7)',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { grid: baseGrid },
                    y: { grid: baseGrid }
                }
            }
        });
    </script>
</x-app-layout>
