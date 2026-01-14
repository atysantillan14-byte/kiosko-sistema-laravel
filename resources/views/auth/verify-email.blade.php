<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-semibold text-slate-900">Verificá tu email</h2>
            <p class="mt-1 text-sm text-slate-500">Te enviamos un enlace para activar tu cuenta.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                Se envió un nuevo enlace de verificación a tu correo.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
            @csrf
            <x-button type="submit" class="w-full">Reenviar enlace</x-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button type="submit" variant="outline" class="w-full">Cerrar sesión</x-button>
        </form>
    </div>
</x-guest-layout>
