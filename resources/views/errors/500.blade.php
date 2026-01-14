<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error del servidor</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="flex min-h-screen items-center justify-center px-6">
        <div class="max-w-lg rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-soft">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-600 text-white">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <h1 class="mt-4 text-3xl font-bold">500</h1>
            <p class="mt-2 text-sm text-slate-500">Ocurrió un error inesperado. Por favor, intentá nuevamente en unos minutos.</p>
            <div class="mt-6 flex flex-col gap-2 sm:flex-row sm:justify-center">
                <a href="{{ route('dashboard') }}" class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white">Volver al dashboard</a>
                <a href="{{ url()->previous() }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600">Reintentar</a>
            </div>
        </div>
    </div>
</body>
</html>
