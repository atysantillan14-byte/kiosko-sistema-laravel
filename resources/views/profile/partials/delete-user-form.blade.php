<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-slate-900">Eliminar cuenta</h2>
        <p class="mt-1 text-sm text-slate-500">Esta acción no se puede deshacer. Se eliminarán todos los datos asociados.</p>
    </header>

    <x-button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        Eliminar cuenta
    </x-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6 p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-slate-900">¿Seguro que querés eliminar tu cuenta?</h2>
            <p class="text-sm text-slate-500">Ingresá tu contraseña para confirmar la eliminación.</p>

            <x-input name="password" type="password" label="Contraseña" />

            <div class="flex justify-end gap-2">
                <x-button variant="ghost" x-on:click="$dispatch('close-modal', 'confirm-user-deletion')">Cancelar</x-button>
                <x-button variant="danger" type="submit">Eliminar definitivamente</x-button>
            </div>
        </form>
    </x-modal>
</section>
