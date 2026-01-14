<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

           {{-- Logo + nombre negocio --}}
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('img/logo.png') }}"
                        alt="Despensa Olivia"
                        class="mx-auto h-16 w-auto"
                        onerror="this.style.display='none';" />
 
                     
                <div class="mt-3 text-xl font-extrabold text-gray-900">
                    {{ env('APP_BUSINESS_NAME', 'Despensa Olivia') }}
                </div>
                <div class="text-sm text-gray-500">Sistema de ventas y control</div>
            </div>

            {{-- Session Status --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email"
                                  class="block mt-1 w-full"
                                  type="email"
                                  name="email"
                                  :value="old('email')"
                                  required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password"
                                  class="block mt-1 w-full"
                                  type="password"
                                  name="password"
                                  required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                               name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-600 hover:text-gray-900 underline"
                           href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 rounded-md bg-gray-900 text-white font-semibold hover:bg-gray-800">
                    {{ __('Log in') }}
                </button>
            </form>

        </div>

        <div class="text-center text-xs text-gray-500 mt-4">
            © {{ date('Y') }} {{ env('APP_BUSINESS_NAME', 'Mi Kiosko') }} — Todos los derechos reservados.
        </div>
    </div>
</x-guest-layout>

