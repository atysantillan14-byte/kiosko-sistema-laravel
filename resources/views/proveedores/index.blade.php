<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Proveedores</h2>
                <p class="app-subtitle">Organizá contactos, acuerdos y abastecimiento del kiosko.</p>
            </div>
            <button class="app-btn-secondary" type="button">
                <i class="fas fa-user-plus"></i>
                Nuevo proveedor
            </button>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="app-card p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Sección en preparación</h3>
                    <p class="mt-2 text-sm text-slate-500">
                        Aquí podrás cargar proveedores, registrar condiciones de pago y controlar entregas.
                    </p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200/70 bg-slate-50/80 px-4 py-3 text-sm text-slate-600">
                    <span class="inline-flex h-2 w-2 rounded-full bg-blue-500/70"></span>
                    Próximamente disponible
                </div>
            </div>

            <div class="mt-8">
                <p class="text-sm font-semibold text-slate-700">Qué vas a poder hacer</p>
                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="app-card-soft p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Contactos centralizados</p>
                        <p class="mt-2 text-sm text-slate-600">Acceso rápido a teléfonos, mails y responsables.</p>
                    </div>
                    <div class="app-card-soft p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Calendario de entregas</p>
                        <p class="mt-2 text-sm text-slate-600">Seguimiento de abastecimiento en tiempo real.</p>
                    </div>
                    <div class="app-card-soft p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Condiciones y acuerdos</p>
                        <p class="mt-2 text-sm text-slate-600">Notas rápidas para negociar mejor stock.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
