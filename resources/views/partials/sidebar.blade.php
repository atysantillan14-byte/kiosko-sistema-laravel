<div class="h-full flex flex-col">
    <!-- Logo en sidebar -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-center">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center">
                <i class="fas fa-store text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Menú de navegación -->
    <nav class="flex-1 p-4 space-y-2">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-500' : 'hover:bg-gray-100 text-gray-700' }}">
            <i class="fas fa-tachometer-alt w-6 mr-3"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        
        <a href="{{ route('categorias.index') }}" 
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('categorias.*') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-500' : 'hover:bg-gray-100 text-gray-700' }}">
            <i class="fas fa-tags w-6 mr-3"></i>
            <span class="font-medium">Categorías</span>
        </a>
        
        <a href="{{ route('productos.index') }}" 
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('productos.*') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-500' : 'hover:bg-gray-100 text-gray-700' }}">
            <i class="fas fa-box w-6 mr-3"></i>
            <span class="font-medium">Productos</span>
            @isset($stats)
            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">
                {{ $stats['productosSinStock'] ?? 0 }}
            </span>
            @endisset
        </a>
        
        <a href="{{ route('ventas.index') }}" 
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('ventas.*') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-500' : 'hover:bg-gray-100 text-gray-700' }}">
            <i class="fas fa-shopping-cart w-6 mr-3"></i>
            <span class="font-medium">Ventas</span>
        </a>
        
        <a href="#" 
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-gray-100 text-gray-700">
            <i class="fas fa-chart-line w-6 mr-3"></i>
            <span class="font-medium">Reportes</span>
        </a>
        
        <a href="#" 
           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-gray-100 text-gray-700">
            <i class="fas fa-users w-6 mr-3"></i>
            <span class="font-medium">Clientes</span>
        </a>
        
        <!-- Separador -->
        <div class="pt-4 mt-4 border-t border-gray-200">
            <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                Configuración
            </p>
            
            <a href="#" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <i class="fas fa-cog w-6 mr-3"></i>
                <span class="font-medium">Sistema</span>
            </a>
            
            <a href="#" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <i class="fas fa-user-shield w-6 mr-3"></i>
                <span class="font-medium">Usuarios</span>
            </a>
            
            <a href="#" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <i class="fas fa-database w-6 mr-3"></i>
                <span class="font-medium">Backup</span>
            </a>
        </div>
    </nav>
    
    <!-- Estado del sistema -->
    <div class="p-4 border-t border-gray-200">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Sistema operativo</p>
                    <p class="text-sm text-gray-600">Todos los servicios funcionando</p>
                </div>
            </div>
        </div>
    </div>
</div>
