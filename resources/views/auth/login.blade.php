<x-guest-layout>
    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <img src="{{ asset('img/logo.png') }}"
                 alt="Despensa Olivia"
                 class="mx-auto h-16 w-auto"
                 onerror="this.style.display='none';" />
            <div class="mt-3 text-xl font-semibold text-slate-900">
                {{ env('APP_BUSINESS_NAME', 'Despensa Olivia') }}
            </div>
            <div class="text-sm text-slate-500">Sistema de ventas y control</div>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-200" name="remember">
                    {{ __('Remember me') }}
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-slate-500 underline hover:text-slate-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <button type="submit" class="app-btn-primary w-full">
                {{ __('Log in') }}
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500">
            © {{ date('Y') }} {{ env('APP_BUSINESS_NAME', 'Mi Kiosko') }} — Todos los derechos reservados.
        </div>
    </div>
</x-guest-layout>
