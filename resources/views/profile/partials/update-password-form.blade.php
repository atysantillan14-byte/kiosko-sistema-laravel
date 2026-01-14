<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-slate-900">Actualizar contraseña</h2>
        <p class="mt-1 text-sm text-slate-500">Usá una contraseña fuerte y actualizala periódicamente.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <x-input name="current_password" type="password" label="Contraseña actual" autocomplete="current-password" />
        <x-input name="password" type="password" label="Nueva contraseña" autocomplete="new-password" />
        <x-input name="password_confirmation" type="password" label="Confirmar contraseña" autocomplete="new-password" />

        <div class="flex items-center gap-3">
            <x-button type="submit">Actualizar</x-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-slate-500"
                >Actualizada.</p>
            @endif
        </div>
    </form>
</section>
