<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-semibold text-slate-900">Bienvenido de nuevo</h2>
            <p class="mt-1 text-sm text-slate-500">Ingresá a tu panel para continuar.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <x-input
                name="email"
                type="email"
                label="Email"
                :value="old('email')"
                placeholder="tu@empresa.com"
                required
                autofocus
                autocomplete="username"
            />

            <x-input
                name="password"
                type="password"
                label="Contraseña"
                placeholder="••••••••"
                required
                autocomplete="current-password"
            />

            <div class="flex items-center justify-between">
                <x-checkbox name="remember" label="Recordarme" />

                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-brand-600 hover:text-brand-700" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <x-button type="submit" class="w-full">Ingresar</x-button>
        </form>
    </div>
</x-guest-layout>
