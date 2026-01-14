<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-semibold text-slate-900">Crear cuenta</h2>
            <p class="mt-1 text-sm text-slate-500">Completá tus datos para comenzar.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <x-input name="name" label="Nombre" :value="old('name')" required autofocus />
            <x-input name="email" type="email" label="Email" :value="old('email')" required />
            <x-input name="password" type="password" label="Contraseña" required autocomplete="new-password" />
            <x-input name="password_confirmation" type="password" label="Confirmar contraseña" required autocomplete="new-password" />

            <x-button type="submit" class="w-full">Crear cuenta</x-button>
        </form>

        <p class="text-center text-xs text-slate-500">
            ¿Ya tenés cuenta? <a class="font-semibold text-brand-600 hover:text-brand-700" href="{{ route('login') }}">Iniciar sesión</a>
        </p>
    </div>
</x-guest-layout>
