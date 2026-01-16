<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Usuarios</h2>
                <p class="app-subtitle">Creá accesos para nuevos empleados y administradores.</p>
            </div>
        </div>
    </x-slot>

    <div class="app-page space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                <div class="font-semibold">Revisá los datos antes de continuar</div>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="app-card p-6 lg:col-span-1">
                <h3 class="text-sm font-semibold text-slate-900">Crear usuario</h3>
                <p class="mt-1 text-xs text-slate-500">Definí el rol y credenciales del nuevo integrante.</p>

                <form method="POST" action="{{ route('usuarios.store') }}" class="mt-4 space-y-4">
                    @csrf

                    <div>
                        <label class="app-label">Nombre</label>
                        <input class="app-input" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label class="app-label">Email</label>
                        <input class="app-input" name="email" type="email" value="{{ old('email') }}" required>
                    </div>

                    <div>
                        <label class="app-label">Rol</label>
                        <select class="app-input" name="role" required>
                            <option value="empleado" @selected(old('role') === 'empleado')>Empleado</option>
                            <option value="admin" @selected(old('role') === 'admin')>Administrador</option>
                        </select>
                    </div>

                    <div>
                        <label class="app-label">Contraseña</label>
                        <input class="app-input" name="password" type="password" required>
                    </div>

                    <div>
                        <label class="app-label">Confirmar contraseña</label>
                        <input class="app-input" name="password_confirmation" type="password" required>
                    </div>

                    <button class="app-btn-primary w-full" type="submit">
                        <i class="fas fa-user-plus"></i>
                        Crear usuario
                    </button>
                </form>
            </div>

            <div class="app-card overflow-hidden lg:col-span-2">
                <div class="overflow-x-auto">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Alta</th>
                                <th class="w-32 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70">
                            @forelse($usuarios as $usuario)
                                <tr>
                                    <td class="font-semibold text-slate-900">{{ $usuario->name }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        <div class="flex flex-col gap-2">
                                            <span class="app-chip {{ $usuario->role === 'admin' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600' }}">
                                                {{ $usuario->role }}
                                            </span>
                                            @if ($usuario->id !== auth()->id())
                                                <form class="flex flex-wrap items-center gap-2" method="POST" action="{{ route('usuarios.role', $usuario) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select class="app-input h-8 py-1 text-xs" name="role" required>
                                                        <option value="empleado" @selected($usuario->role === 'empleado')>Empleado</option>
                                                        <option value="admin" @selected($usuario->role === 'admin')>Administrador</option>
                                                    </select>
                                                    <button class="rounded-full border border-blue-200 px-3 py-1 text-xs font-semibold text-blue-700 transition hover:border-blue-300 hover:bg-blue-50" type="submit">
                                                        Actualizar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $usuario->created_at?->format('d/m/Y') }}</td>
                                    <td class="text-right">
                                        <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-3 py-1 text-xs font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-50"
                                                type="submit"
                                                @disabled($usuario->id === auth()->id())
                                                onclick="return confirm('¿Querés eliminar este usuario?')"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-10 text-center text-sm text-slate-500" colspan="5">
                                        <div class="flex flex-col items-center gap-2">
                                            <i class="fas fa-users text-2xl text-slate-300"></i>
                                            No hay usuarios registrados.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
