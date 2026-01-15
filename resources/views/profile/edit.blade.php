<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="app-title">Perfil</h2>
            <p class="app-subtitle">Actualizá tu información y credenciales de acceso.</p>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="space-y-6">
            <div class="app-card p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="app-card p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="app-card p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
