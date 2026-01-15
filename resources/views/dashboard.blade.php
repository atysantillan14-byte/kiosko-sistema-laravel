<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="app-title">Inicio</h2>
            <p class="app-subtitle">Acceso rápido al sistema.</p>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="app-card p-6">
            <div class="text-sm text-slate-600">
                {{ __("You're logged in!") }}
            </div>
        </div>
    </div>
</x-app-layout>
