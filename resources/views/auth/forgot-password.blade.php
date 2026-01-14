<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-semibold text-slate-900">Recuperar acceso</h2>
            <p class="mt-1 text-sm text-slate-500">Te enviaremos un enlace para restablecer tu contraseña.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <x-input name="email" type="email" label="Email" :value="old('email')" required autofocus />
            <x-button type="submit" class="w-full">Enviar enlace</x-button>
        </form>

        <p class="text-center text-xs text-slate-500">
            ¿Recordaste tu contraseña? <a class="font-semibold text-brand-600 hover:text-brand-700" href="{{ route('login') }}">Volver</a>
        </p>
    </div>
</x-guest-layout>
