<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar venta #{{ $venta->id }}
            </h2>

            <a href="{{ route('ventas.index') }}"
               class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ route('ventas.update', $venta) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Usuario</label>
                        <select name="user_id" class="mt-1 w-full rounded-lg border-gray-300">
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}" @selected((string)$venta->user_id === (string)$u->id)>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Método de pago</label>
                        <input name="metodo_pago" value="{{ old('metodo_pago', $venta->metodo_pago) }}"
                               class="mt-1 w-full rounded-lg border-gray-300" />
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Estado</label>
                        <input name="estado" value="{{ old('estado', $venta->estado) }}"
                               class="mt-1 w-full rounded-lg border-gray-300" />
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('ventas.index') }}"
                           class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                            Cancelar
                        </a>
                        <button class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                            Actualizar
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-xs text-gray-500">
                    Nota: la edición de ítems (productos) se puede agregar después (para no romper stock y totales).
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

