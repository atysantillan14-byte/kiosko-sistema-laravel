<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Cierre de caja</h2>
                <p class="app-subtitle">Resumen del turno y conciliación rápida para el cajero.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('ventas.index') }}" class="app-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver a ventas
                </a>
                <a href="{{ route('ventas.cierres.index') }}" class="app-btn-secondary">
                    <i class="fas fa-list"></i>
                    Cierres guardados
                </a>
            </div>
        </div>
    </x-slot>

    <div class="app-page space-y-6" x-data="{
        efectivoVentas: {{ (float) $efectivoVentas }},
        fondoInicial: 0,
        ingresos: 0,
        retiros: 0,
        devoluciones: 0,
        efectivoContado: 0,
        observaciones: '',
        denominaciones: [20000, 10000, 2000, 1000, 500, 100, 50, 20, 10],
        conteo: {
            20000: 0,
            10000: 0,
            2000: 0,
            1000: 0,
            500: 0,
            100: 0,
            50: 0,
            20: 0,
            10: 0,
        },
        get efectivoEsperado() {
            return this.efectivoVentas + this.fondoInicial + this.ingresos - this.retiros - this.devoluciones;
        },
        get totalConteo() {
            return this.denominaciones.reduce((total, denominacion) => {
                return total + (Number(this.conteo[denominacion]) || 0) * denominacion;
            }, 0);
        },
        get diferencia() {
            return this.efectivoContado - this.efectivoEsperado;
        },
        get diferenciaClase() {
            return this.diferencia >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700';
        },
        formatCurrency(valor) {
            return new Intl.NumberFormat('es-CL', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(valor ?? 0);
        }
    }">
        <form id="cierre-guardar-form" method="POST" action="{{ route('ventas.cierre.guardar') }}">
            @csrf
            <input type="hidden" name="desde" value="{{ $rangos['desde'] }}">
            <input type="hidden" name="hasta" value="{{ $rangos['hasta'] }}">
            <input type="hidden" name="hora_desde" value="{{ $rangos['hora_desde'] }}">
            <input type="hidden" name="hora_hasta" value="{{ $rangos['hora_hasta'] }}">
            <input type="hidden" name="turno" value="{{ $rangos['turno'] }}">
            <input type="hidden" name="fondo_inicial" x-model="fondoInicial">
            <input type="hidden" name="ingresos" x-model="ingresos">
            <input type="hidden" name="retiros" x-model="retiros">
            <input type="hidden" name="devoluciones" x-model="devoluciones">
            <input type="hidden" name="efectivo_contado" x-model="efectivoContado">
            <input type="hidden" name="diferencia" :value="diferencia">
            <input type="hidden" name="observaciones" x-model="observaciones">
        </form>

        <div class="app-card p-5 print-only">
            <h3 class="text-sm font-semibold text-slate-900">Resumen del cierre</h3>
            <div class="mt-3 grid grid-cols-1 gap-3 text-sm text-slate-700 md:grid-cols-3">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Turno</p>
                    <p class="font-semibold text-slate-900">{{ $rangos['turno'] ? ucfirst($rangos['turno']) : 'Todos' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Rango</p>
                    <p class="font-semibold text-slate-900">
                        {{ $rangoInicio ? $rangoInicio->format('d/m/Y H:i') : 'Sin datos' }}
                        —
                        {{ $rangoFin ? $rangoFin->format('d/m/Y H:i') : 'Sin datos' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Horas</p>
                    <p class="font-semibold text-slate-900">
                        {{ $rangos['hora_desde'] ?: '00:00' }} - {{ $rangos['hora_hasta'] ?: '23:59' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="app-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Filtros del turno</h3>
                    <p class="text-xs text-slate-500">Definí el rango y el turno para generar el cierre.</p>
                </div>
            </div>

            <form class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-7" method="GET" action="{{ route('ventas.cierre') }}">
                <label class="text-xs font-semibold text-slate-500">
                    Desde
                    <input class="app-input mt-1" type="date" name="desde" value="{{ $rangos['desde'] }}">
                </label>

                <label class="text-xs font-semibold text-slate-500">
                    Hasta
                    <input class="app-input mt-1" type="date" name="hasta" value="{{ $rangos['hasta'] }}">
                </label>

                <label class="text-xs font-semibold text-slate-500">
                    Turno
                    <select class="app-input mt-1" name="turno">
                        <option value="">Todos</option>
                        <option value="manana" @selected($rangos['turno'] === 'manana')>Mañana (06:00 - 13:59)</option>
                        <option value="tarde" @selected($rangos['turno'] === 'tarde')>Tarde (14:00 - 21:59)</option>
                        <option value="noche" @selected($rangos['turno'] === 'noche')>Noche (22:00 - 23:59)</option>
                    </select>
                </label>

                <label class="text-xs font-semibold text-slate-500">
                    Hora desde
                    <input class="app-input mt-1" type="time" name="hora_desde" value="{{ $rangos['hora_desde'] }}">
                </label>

                <label class="text-xs font-semibold text-slate-500">
                    Hora hasta
                    <input class="app-input mt-1" type="time" name="hora_hasta" value="{{ $rangos['hora_hasta'] }}">
                </label>

                <div class="flex items-end gap-2 md:col-span-2 xl:col-span-2">
                    <button class="app-btn-primary w-full" type="submit">
                        <i class="fas fa-chart-line"></i>
                        Generar cierre
                    </button>
                    <a href="{{ route('ventas.cierre') }}" class="app-btn-secondary w-full">
                        <i class="fas fa-rotate-left"></i>
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-blue-50 text-blue-700">Turno</span>
                    <span class="text-xs font-semibold text-blue-600">Rango</span>
                </div>
                <div class="mt-4 text-sm font-semibold text-slate-900">
                    {{ $rangoInicio ? $rangoInicio->format('d/m/Y H:i') : 'Sin datos' }}
                </div>
                <div class="text-xs text-slate-500">
                    {{ $rangoFin ? $rangoFin->format('d/m/Y H:i') : 'Sin datos' }}
                </div>
                <p class="mt-2 text-xs text-slate-400">
                    Turno: {{ $rangos['turno'] ? ucfirst($rangos['turno']) : 'Todos' }} ·
                    Horas: {{ $rangos['hora_desde'] ?: '00:00' }} - {{ $rangos['hora_hasta'] ?: '23:59' }}.
                </p>
            </div>
            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-emerald-50 text-emerald-700">Total de dinero</span>
                    <span class="text-xs font-semibold text-emerald-600">Total</span>
                </div>
                <div class="mt-4 text-3xl font-semibold text-slate-900">
                    $ {{ number_format((float) $totalNeto, 2, ',', '.') }}
                </div>
                <p class="mt-2 text-xs text-slate-500">Bruto: $ {{ number_format((float) $totalBruto, 2, ',', '.') }} · Descuentos: $ {{ number_format((float) $totalDescuentos, 2, ',', '.') }}</p>
            </div>
            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-amber-50 text-amber-700">Total de ventas</span>
                    <span class="text-xs font-semibold text-amber-600">Cantidad</span>
                </div>
                <div class="mt-4 text-3xl font-semibold text-slate-900">{{ (int) $cantidadVentas }}</div>
                <p class="mt-2 text-xs text-slate-500">Promedio diario: $ {{ number_format((float) $promedioDiario, 2, ',', '.') }}</p>
            </div>
            <div class="app-card p-6">
                <div class="flex items-center justify-between">
                    <span class="app-chip bg-slate-100 text-slate-700">Cajero</span>
                    <span class="text-xs font-semibold text-slate-600">Usuario</span>
                </div>
                <div class="mt-4 text-lg font-semibold text-slate-900">{{ auth()->user()->name }}</div>
                <p class="mt-2 text-xs text-slate-500">Generado automáticamente para este turno.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            <div class="app-card overflow-hidden print-avoid-break xl:col-span-2">
                <div class="border-b border-slate-200/70 px-5 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Desglose por medio de pago</h3>
                    <p class="text-xs text-slate-500">Montos y cantidad de transacciones por medio.</p>
                </div>
                <div class="overflow-x-auto print-overflow-visible">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Medio de pago</th>
                                <th class="text-right">Monto</th>
                                <th class="text-right"># Transacciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70">
                            @forelse($desglosePagos as $pago)
                                <tr>
                                    <td class="font-semibold text-slate-900">{{ ucfirst($pago['metodo']) }}</td>
                                    <td class="text-right">$ {{ number_format((float) $pago['monto'], 2, ',', '.') }}</td>
                                    <td class="text-right">{{ $pago['transacciones'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-6 text-center text-sm text-slate-500" colspan="3">
                                        Sin ventas registradas en el período.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="app-card p-6">
                <h3 class="text-sm font-semibold text-slate-900">Movimientos especiales</h3>
                <p class="mt-1 text-xs text-slate-500">Eventos que impactan el cierre.</p>
                <dl class="mt-4 space-y-3 text-sm text-slate-600">
                    <div class="flex items-center justify-between">
                        <dt>Anulaciones</dt>
                        <dd class="font-semibold text-slate-900">{{ $anulacionesCantidad }} · $ {{ number_format((float) $anulacionesTotal, 2, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Devoluciones</dt>
                        <dd class="font-semibold text-slate-900">0 · $ 0,00</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Descuentos manuales</dt>
                        <dd class="font-semibold text-slate-900">0 · $ 0,00</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Propinas</dt>
                        <dd class="font-semibold text-slate-900">0 · $ 0,00</dd>
                    </div>
                </dl>
                <p class="mt-4 text-xs text-slate-400">Los conceptos sin registro quedan en cero hasta que se configuren.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
            <div class="app-card p-6 print-avoid-break">
                <h3 class="text-sm font-semibold text-slate-900">Detalle del efectivo esperado</h3>
                <p class="mt-1 text-xs text-slate-500">Incluye caja chica e ingresos/retiros manuales.</p>
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    <div class="flex items-center justify-between">
                        <span>Efectivo de ventas</span>
                        <span class="font-semibold text-slate-900">$ {{ number_format((float) $efectivoVentas, 2, ',', '.') }}</span>
                    </div>
                    <label class="flex items-center justify-between gap-3 text-sm text-slate-600">
                        Fondo inicial (caja chica)
                        <input x-model.number="fondoInicial" type="number" step="0.01" min="0" class="app-input w-32 text-right print-hidden" placeholder="0,00">
                        <span class="print-only font-semibold text-slate-900">$ <span x-text="fondoInicial.toFixed(2)"></span></span>
                    </label>
                    <label class="flex items-center justify-between gap-3 text-sm text-slate-600">
                        Ingresos manuales
                        <input x-model.number="ingresos" type="number" step="0.01" min="0" class="app-input w-32 text-right print-hidden" placeholder="0,00">
                        <span class="print-only font-semibold text-slate-900">$ <span x-text="ingresos.toFixed(2)"></span></span>
                    </label>
                    <label class="flex items-center justify-between gap-3 text-sm text-slate-600">
                        Retiros manuales
                        <input x-model.number="retiros" type="number" step="0.01" min="0" class="app-input w-32 text-right print-hidden" placeholder="0,00">
                        <span class="print-only font-semibold text-slate-900">$ <span x-text="retiros.toFixed(2)"></span></span>
                    </label>
                    <label class="flex items-center justify-between gap-3 text-sm text-slate-600">
                        Devoluciones en efectivo
                        <input x-model.number="devoluciones" type="number" step="0.01" min="0" class="app-input w-32 text-right print-hidden" placeholder="0,00">
                        <span class="print-only font-semibold text-slate-900">$ <span x-text="devoluciones.toFixed(2)"></span></span>
                    </label>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-200/70 pt-3 text-2xl font-semibold text-slate-900">
                        <span class="text-sm">Efectivo esperado</span>
                        <span>$ <span x-text="efectivoEsperado.toFixed(2)"></span></span>
                    </div>
                </div>
            </div>

            <div class="app-card p-6">
                <h3 class="text-sm font-semibold text-slate-900">Conciliación rápida</h3>
                <p class="mt-1 text-xs text-slate-500">Ingresá el efectivo contado y registrá diferencias.</p>
                <div class="mt-4 space-y-4">
                    <label class="text-xs font-semibold text-slate-500">
                        Efectivo contado
                        <input x-model.number="efectivoContado" type="number" step="0.01" min="0" class="app-input mt-2 print-hidden" placeholder="0,00">
                        <span class="print-only mt-2 block font-semibold text-slate-900">$ <span x-text="efectivoContado.toFixed(2)"></span></span>
                    </label>
                    <div class="rounded-xl border border-slate-200/70 p-4 text-sm">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <p class="text-xs font-semibold text-slate-700">Conteo de billetes y monedas</p>
                                <p class="text-xs text-slate-500">Ingresá la cantidad por denominación.</p>
                            </div>
                            <div class="text-xs font-semibold text-slate-900">
                                Total: $ <span x-text="formatCurrency(totalConteo)"></span>
                            </div>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            <template x-for="denominacion in denominaciones" :key="denominacion">
                                <label class="flex items-center justify-between gap-2 text-xs font-semibold text-slate-500">
                                    <span>$ <span x-text="denominacion"></span></span>
                                    <input x-model.number="conteo[denominacion]" type="number" min="0" class="app-input w-20 text-right print-hidden" placeholder="0">
                                    <span class="print-only font-semibold text-slate-900" x-text="conteo[denominacion]"></span>
                                </label>
                            </template>
                        </div>
                        <button type="button" class="app-btn-secondary mt-4 w-full print-hidden" @click="efectivoContado = totalConteo">
                            <i class="fas fa-arrow-down"></i>
                            Usar total en efectivo contado
                        </button>
                    </div>
                    <div class="rounded-xl border border-slate-200/70 p-4 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Diferencia</span>
                            <span class="app-chip" :class="diferenciaClase">
                                $ <span x-text="diferencia.toFixed(2)"></span>
                            </span>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Verde si el saldo es positivo, rojo si es negativo.</p>
                    </div>
                    <label class="text-xs font-semibold text-slate-500">
                        Observaciones del cajero
                        <textarea x-model="observaciones" class="app-input mt-2 h-24" placeholder="Anotá motivos de diferencias, ajustes o aclaraciones..."></textarea>
                    </label>
                    <div class="rounded-xl bg-slate-50 p-3 text-xs text-slate-500">
                        Firma/usuario del cierre: <span class="font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-card overflow-hidden">
            <div class="border-b border-slate-200/70 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">Productos vendidos en el cierre</h3>
                <p class="text-xs text-slate-500">Detalle de los productos vendidos en este turno.</p>
            </div>
            <div class="overflow-x-auto print-overflow-visible">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-right">Cantidad</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70">
                        @forelse($productosVendidos as $producto)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $producto['producto'] }}</td>
                                <td class="text-right">{{ number_format((float) $producto['cantidad'], 2, ',', '.') }}</td>
                                <td class="text-right">$ {{ number_format((float) $producto['total'], 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-6 text-center text-sm text-slate-500" colspan="3">
                                    Sin productos vendidos en el período.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end print-hidden">
            <button form="cierre-guardar-form" type="submit" class="app-btn-primary">
                <i class="fas fa-save"></i>
                Guardar cierre
            </button>
        </div>
    </div>
</x-app-layout>
