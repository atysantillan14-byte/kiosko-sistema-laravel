<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Kioskos')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <i class="fas fa-store text-2xl text-blue-500 mr-3"></i>
                    <h1 class="text-2xl font-bold text-gray-800">Sistema Kioskos</h1>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-500">
                        <i class="fas fa-home mr-1"></i> Inicio
                    </a>
                    <a href="{{ route('categorias.index') }}" class="text-gray-700 hover:text-blue-500">
                        <i class="fas fa-tags mr-1"></i> Categorías
                    </a>
                    <a href="{{ route('productos.index') }}" class="text-gray-700 hover:text-blue-500">
                        <i class="fas fa-box mr-1"></i> Productos
                    </a>
                    <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-blue-500">
                        <i class="fas fa-chart-bar mr-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido -->
    <div class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p>Sistema de Kioskos &copy; {{ date('Y') }} - Desarrollado con Laravel</p>
            <p class="text-gray-400 text-sm mt-2">
                <i class="fas fa-code"></i> by atysantillan14-byte
            </p>
        </div>
    </footer>
</body>
</html>