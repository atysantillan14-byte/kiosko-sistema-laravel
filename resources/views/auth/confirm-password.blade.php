<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-semibold text-slate-900">Confirmá tu contraseña</h2>
            <p class="mt-1 text-sm text-slate-500">Necesitamos validar tu identidad antes de continuar.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf
            <x-input name="password" type="password" label="Contraseña" required autocomplete="current-password" />
            <x-button type="submit" class="w-full">Confirmar</x-button>
        </form>
    </div>
</x-guest-layout>
