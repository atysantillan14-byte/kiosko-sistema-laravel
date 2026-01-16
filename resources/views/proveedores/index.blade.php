<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Proveedores</h2>
                <p class="app-subtitle">Organizá contactos, acuerdos y abastecimiento del kiosko.</p>
            </div>
            <a class="app-btn-primary" href="{{ route('proveedores.create') }}">
                <i class="fas fa-user-plus"></i>
                Nuevo proveedor
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="app-card overflow-hidden">
            @if (session('success'))
                <div class="border-b border-emerald-200/70 bg-emerald-50 px-6 py-4 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="border-b border-rose-200/70 bg-rose-50 px-6 py-4 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif
            <div class="overflow-x-auto">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Proveedor</th>
                            <th>Contacto</th>
                            <th>Condiciones</th>
                            <th>Productos</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70">
                        @forelse($proveedores as $proveedor)
                            <tr>
                                <td>
                                    <div class="font-semibold text-slate-900">{{ $proveedor->nombre }}</div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $proveedor->email ?: 'Sin email' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm text-slate-700">{{ $proveedor->contacto ?: 'Sin responsable' }}</div>
                                    <div class="mt-1 text-xs text-slate-500">{{ $proveedor->telefono ?: 'Sin teléfono' }}</div>
                                </td>
                                <td>
                                    <div class="text-sm text-slate-700">{{ $proveedor->condiciones_pago ?: 'Sin condiciones registradas' }}</div>
                                    <div class="mt-1 text-xs text-slate-500">{{ $proveedor->direccion ?: 'Sin dirección' }}</div>
                                </td>
                                <td>
                                    <div class="text-sm text-slate-700">
                                        {{ $proveedor->productos ?: 'Sin productos registrados' }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $proveedor->cantidad !== null ? $proveedor->cantidad . ' unidades' : 'Sin cantidad' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="app-chip {{ $proveedor->activo ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $proveedor->activo ? 'activo' : 'inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a class="app-btn-secondary px-3 py-1.5 text-xs" href="{{ route('proveedores.edit', $proveedor) }}">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('proveedores.destroy', $proveedor) }}" onsubmit="return confirm('¿Eliminar este proveedor?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="app-btn-danger px-3 py-1.5 text-xs">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-sm text-slate-500" colspan="6">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-truck text-2xl text-slate-300"></i>
                                        Todavía no registraste proveedores.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
