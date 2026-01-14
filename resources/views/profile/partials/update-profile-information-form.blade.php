<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-slate-900">Información de perfil</h2>
        <p class="mt-1 text-sm text-slate-500">Actualizá nombre y correo asociado.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <x-input name="name" label="Nombre" :value="old('name', $user->name)" required autofocus autocomplete="name" />
        <x-input name="email" type="email" label="Email" :value="old('email', $user->email)" required autocomplete="username" />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                <p>Tu email aún no está verificado.</p>
                <button form="send-verification" class="mt-2 inline-flex text-xs font-semibold text-amber-700 underline">
                    Reenviar email de verificación
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-xs font-semibold text-emerald-600">Se envió un nuevo enlace.</p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-3">
            <x-button type="submit">Guardar cambios</x-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-slate-500"
                >Guardado.</p>
            @endif
        </div>
    </form>
</section>
