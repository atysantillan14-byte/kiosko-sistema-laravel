<div class="flex h-full flex-col">
    <div class="border-b border-slate-200/70 p-6">
        <div class="flex items-center justify-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-500">
                <i class="fas fa-store text-white text-xl"></i>
            </div>
        </div>
    </div>

    <nav class="flex-1 space-y-1 p-4">
        <a href="{{ route('home') }}"
           class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('home') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fas fa-tachometer-alt w-5"></i>
            <span>Inicio</span>
        </a>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fas fa-chart-line w-5"></i>
            <span>Estadísticas</span>
        </a>

        <a href="{{ route('categorias.index') }}"
           class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('categorias.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fas fa-tags w-5"></i>
            <span>Categorías</span>
        </a>

        <a href="{{ route('productos.index') }}"
           class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('productos.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fas fa-box w-5"></i>
            <span>Productos</span>
            @isset($stats)
                <span class="ml-auto rounded-full bg-rose-500 px-2 py-0.5 text-xs font-semibold text-white">
                    {{ $stats['productosSinStock'] ?? 0 }}
                </span>
            @endisset
        </a>

        <a href="{{ route('ventas.index') }}"
           class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('ventas.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fas fa-shopping-cart w-5"></i>
            <span>Ventas</span>
        </a>

        <a href="{{ route('proveedores.index') }}"
           class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('proveedores.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fas fa-truck w-5"></i>
            <span>Proveedores</span>
        </a>

        <a href="#"
           class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
            <i class="fas fa-chart-line w-5"></i>
            <span>Reportes</span>
        </a>

        <a href="#"
           class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
            <i class="fas fa-users w-5"></i>
            <span>Clientes</span>
        </a>

        <div class="mt-4 border-t border-slate-200/70 pt-4">
            <p class="px-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Configuración</p>

            <a href="#"
               class="mt-2 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                <i class="fas fa-cog w-5"></i>
                <span>Sistema</span>
            </a>

            @if ((auth()->user()->role ?? null) === 'admin')
                <a href="{{ route('usuarios.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('usuarios.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fas fa-user-shield w-5"></i>
                    <span>Usuarios</span>
                </a>
            @endif

            <a href="#"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                <i class="fas fa-database w-5"></i>
                <span>Backup</span>
            </a>
        </div>
    </nav>

    <div class="border-t border-slate-200/70 p-4">
        <div class="rounded-2xl bg-gradient-to-r from-emerald-50 to-emerald-100 p-4">
            <div class="flex items-center">
                <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100">
                    <i class="fas fa-check text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">Sistema operativo</p>
                    <p class="text-xs text-slate-600">Todos los servicios funcionando</p>
                </div>
            </div>
        </div>
    </div>
</div>
