<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-semibold text-slate-900">Nueva contraseña</h2>
            <p class="mt-1 text-sm text-slate-500">Elegí una contraseña segura.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <x-input name="email" type="email" label="Email" :value="old('email', $request->email)" required autofocus />
            <x-input name="password" type="password" label="Contraseña" required autocomplete="new-password" />
            <x-input name="password_confirmation" type="password" label="Confirmar contraseña" required autocomplete="new-password" />

            <x-button type="submit" class="w-full">Actualizar contraseña</x-button>
        </form>
    </div>
</x-guest-layout>
