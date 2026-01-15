<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-bold text-slate-900">Proveedores</h2>
            <p class="text-sm text-slate-500">Organizá contactos, acuerdos y abastecimiento del kiosko.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Sección en preparación</h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Aquí podrás cargar proveedores, registrar condiciones de pago y controlar entregas.
                        </p>
                    </div>
                    <button class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-slate-800" type="button">
                        <i class="fas fa-user-plus"></i>
                        Nuevo proveedor
                    </button>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Contactos centralizados</p>
                        <p class="mt-2 text-sm text-slate-600">Acceso rápido a teléfonos, mails y responsables.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Calendario de entregas</p>
                        <p class="mt-2 text-sm text-slate-600">Seguimiento de abastecimiento en tiempo real.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Condiciones y acuerdos</p>
                        <p class="mt-2 text-sm text-slate-600">Notas rápidas para negociar mejor stock.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
