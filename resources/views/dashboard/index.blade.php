@extends("layouts.app")

@section("title", "Dashboard - KioskoPro")
@section("page-title", "Panel de Control")

@section("content")
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Categorías</p>
                    <p class="text-3xl font-bold">{{ $stats["totalCategorias"] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-tags text-blue-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('categorias.index') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                Ver todas →
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Productos</p>
                    <p class="text-3xl font-bold">{{ $stats["totalProductos"] ?? 0 }}</p>
                    <p class="text-sm text-red-500 mt-1">{{ $stats["productosSinStock"] ?? 0 }} sin stock</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-box text-green-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('productos.index') }}" class="inline-block mt-4 text-green-600 hover:text-green-800">
                Ver todos →
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Usuarios</p>
                    <p class="text-3xl font-bold">{{ $stats["totalUsuarios"] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
            <a href="#" class="inline-block mt-4 text-purple-600 hover:text-purple-800">
                Gestionar →
            </a>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4">Acciones Rápidas</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('categorias.create') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center">
                <i class="fas fa-plus-circle text-blue-600 text-2xl mb-2"></i>
                <p class="font-medium">Nueva Categoría</p>
            </a>
            
            <a href="{{ route('productos.create') }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center">
                <i class="fas fa-plus-circle text-green-600 text-2xl mb-2"></i>
                <p class="font-medium">Nuevo Producto</p>
            </a>
            
            <a href="{{ route('categorias.index') }}" class="p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg text-center">
                <i class="fas fa-list text-yellow-600 text-2xl mb-2"></i>
                <p class="font-medium">Ver Categorías</p>
            </a>
            
            <a href="{{ route('productos.index') }}" class="p-4 bg-red-50 hover:bg-red-100 rounded-lg text-center">
                <i class="fas fa-list text-red-600 text-2xl mb-2"></i>
                <p class="font-medium">Ver Productos</p>
            </a>
        </div>
    </div>
    
    <div class="text-center p-8 bg-gradient-to-r from-blue-50 to-gray-100 rounded-xl">
        <h3 class="text-2xl font-bold text-gray-800 mb-2">¡Sistema Kiosko funcionando!</h3>
        <p class="text-gray-600 mb-4">El dashboard está cargando correctamente. Ahora puedes gestionar categorías y productos.</p>
        <div class="flex justify-center space-x-4">
            <a href="{{ route('categorias.index') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Comenzar con Categorías
            </a>
            <a href="{{ route('productos.index') }}" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Comenzar con Productos
            </a>
        </div>
    </div>
</div>
@endsection
