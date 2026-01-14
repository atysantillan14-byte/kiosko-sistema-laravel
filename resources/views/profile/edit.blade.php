<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Mi cuenta</h2>
            <p class="text-sm text-slate-500">Gestioná tu perfil, contraseña y opciones de seguridad.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <x-card title="Información de perfil" description="Actualizá tu nombre, email y preferencias de verificación.">
            <div class="max-w-2xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </x-card>

        <x-card title="Seguridad" description="Actualizá tu contraseña de acceso periódicamente.">
            <div class="max-w-2xl">
                @include('profile.partials.update-password-form')
            </div>
        </x-card>

        <x-card title="Eliminar cuenta" description="Acción irreversible. Esta operación elimina tu usuario.">
            <div class="max-w-2xl">
                @include('profile.partials.delete-user-form')
            </div>
        </x-card>
    </div>
</x-app-layout>
