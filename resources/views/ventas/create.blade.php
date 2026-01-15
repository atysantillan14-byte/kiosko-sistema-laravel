<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nueva venta (Caja)</h2>
            <a href="{{ route('ventas.index') }}"
               class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white/90 border border-slate-100 shadow-xl rounded-2xl p-6 sm:p-7">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-900">Detalles de la venta</h3>
                    <p class="text-sm text-slate-500">Seleccioná productos, cantidades y el método de pago.</p>
                </div>

                <form method="POST" action="{{ route('ventas.store') }}" class="space-y-6" id="ventaForm">
                    @csrf

                    {{-- Fix: tu store() requiere user_id y estado --}}
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="estado" value="confirmada">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Producto</label>
                            <input id="productoSearch" type="search" placeholder="Buscar y seleccionar producto..."
                                   class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="off" list="productoSuggestions">
                            <datalist id="productoSuggestions"></datalist>
                            <select id="productoSelect" class="sr-only" aria-hidden="true">
                                <option value="">Seleccione...</option>
                                @foreach($productos as $p)
                                    <option value="{{ $p->id }}"
                                            data-nombre="{{ $p->nombre }}"
                                            data-precio="{{ $p->precio_descuento ?? $p->precio }}"
                                            data-stock="{{ $p->stock ?? '' }}"
                                            data-categoria="{{ $p->categoria?->nombre ?? '' }}">
                                        {{ $p->nombre }} ({{ $p->categoria?->nombre ?? 'Sin categoría' }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-500 mt-2" id="productoInfo"></p>
                            <div id="agregarWrapper" class="mt-3 hidden">
                                <button type="button" id="agregarBtn"
                                        class="w-full sm:w-auto px-4 py-2 rounded-xl bg-slate-900 text-white shadow-sm transition hover:bg-slate-800">
                                    Agregar producto
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Cantidad</label>
                            <input id="cantidadInput" type="number" min="1" value="1" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="font-semibold text-slate-900 mb-3">Carrito</h3>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm" id="tablaCarrito">
                                <thead>
                                <tr class="border-b border-slate-200 text-slate-600">
                                    <th class="py-2">Producto</th>
                                    <th class="py-2">Precio</th>
                                    <th class="py-2">Cantidad</th>
                                    <th class="py-2">Subtotal</th>
                                    <th class="py-2 text-right">Acción</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr id="carritoVacio">
                                    <td colspan="5" class="py-3 text-slate-500">No hay productos agregados.</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3 md:items-end">
                            <div class="md:col-span-2 space-y-4">
                                <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2">
                                    <input type="checkbox" id="pagoMixto" name="pago_mixto" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-200">
                                    <label for="pagoMixto" class="text-sm font-semibold text-slate-700">Pago con dos métodos</label>
                                </div>

                                <div id="pagoSimple">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Método de pago</label>
                                    <select name="metodo_pago" id="metodoPagoSimple" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="debito">Débito</option>
                                        <option value="credito">Crédito</option>
                                        <option value="transferencia">Transferencia</option>
                                    </select>
                                </div>

                                <div id="pagoMixtoCampos" class="hidden grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Método 1</label>
                                        <select name="metodo_pago_primario" id="metodoPagoPrimario" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                            <option value="efectivo">Efectivo</option>
                                            <option value="debito">Débito</option>
                                            <option value="credito">Crédito</option>
                                            <option value="transferencia">Transferencia</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Monto 1</label>
                                        <input type="number" step="0.01" min="0" name="monto_primario" id="montoPrimario" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="0,00">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Método 2</label>
                                        <select name="metodo_pago_secundario" id="metodoPagoSecundario" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                            <option value="transferencia">Transferencia</option>
                                            <option value="debito">Débito</option>
                                            <option value="credito">Crédito</option>
                                            <option value="efectivo">Efectivo</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Monto 2</label>
                                        <input type="number" step="0.01" min="0" name="monto_secundario" id="montoSecundario" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="0,00">
                                    </div>
                                </div>

                                <div id="efectivoBox" class="hidden">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Efectivo recibido</label>
                                    <input type="number" step="0.01" min="0" name="efectivo_recibido" id="efectivoRecibido" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="0,00">
                                    <p class="text-xs text-slate-500 mt-1">Vuelto: <span id="vueltoTxt">$ 0,00</span></p>
                                </div>
                            </div>

                            <div class="text-right rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3">
                                <div class="text-sm text-slate-500">Total</div>
                                <div class="text-2xl font-bold text-slate-900" id="totalTxt">$ 0,00</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2 pt-2">
                        <a href="{{ route('ventas.index') }}"
                           class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">
                            Cancelar
                        </a>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white shadow-sm hover:bg-blue-700">
                            Guardar venta
                        </button>
                    </div>

                    <div id="inputsHidden"></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const productoSearch = document.getElementById('productoSearch');
        const productoSelect = document.getElementById('productoSelect');
        const cantidadInput = document.getElementById('cantidadInput');
        const agregarBtn = document.getElementById('agregarBtn');
        const agregarWrapper = document.getElementById('agregarWrapper');
        const carritoVacio = document.getElementById('carritoVacio');
        const tbody = document.querySelector('#tablaCarrito tbody');
        const totalTxt = document.getElementById('totalTxt');
        const inputsHidden = document.getElementById('inputsHidden');
        const productoInfo = document.getElementById('productoInfo');
        const productoSuggestions = document.getElementById('productoSuggestions');
        const ventaForm = document.getElementById('ventaForm');
        const pagoMixto = document.getElementById('pagoMixto');
        const pagoSimple = document.getElementById('pagoSimple');
        const pagoMixtoCampos = document.getElementById('pagoMixtoCampos');
        const metodoPagoSimple = document.getElementById('metodoPagoSimple');
        const metodoPagoPrimario = document.getElementById('metodoPagoPrimario');
        const metodoPagoSecundario = document.getElementById('metodoPagoSecundario');
        const montoPrimario = document.getElementById('montoPrimario');
        const montoSecundario = document.getElementById('montoSecundario');
        const efectivoBox = document.getElementById('efectivoBox');
        const efectivoRecibido = document.getElementById('efectivoRecibido');
        const vueltoTxt = document.getElementById('vueltoTxt');

        let carrito = [];
        let totalActual = 0;
        const opcionesOriginales = Array.from(productoSelect.options);
        let syncingMontos = false;
        let ultimoMontoEditado = 'primario';

        function normalizar(texto){
            return texto.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        }

        function etiquetaProducto(opt) {
            const categoria = opt.dataset.categoria || 'Sin categoría';
            return `${opt.dataset.nombre} (${categoria})`;
        }

        function sincronizarSelectDesdeBusqueda(valor){
            if (!valor) return;
            const buscado = normalizar(valor);
            const match = opcionesOriginales.find((opt) => {
                if (!opt.value) return false;
                const etiqueta = normalizar(etiquetaProducto(opt));
                const nombre = normalizar(opt.dataset.nombre || '');
                return buscado === etiqueta || buscado === nombre;
            });

            if (match) {
                productoSelect.value = match.value;
                productoSelect.dispatchEvent(new Event('change'));
            }
        }

        productoSearch.addEventListener('input', () => {
            const filtro = normalizar(productoSearch.value.trim());
            productoSelect.innerHTML = '';
            productoSuggestions.innerHTML = '';
            const fragment = document.createDocumentFragment();
            const sugerencias = document.createDocumentFragment();

            opcionesOriginales.forEach((opt) => {
                if (!opt.value) {
                    fragment.appendChild(opt.cloneNode(true));
                    return;
                }
                const texto = normalizar(`${opt.dataset.nombre} ${opt.dataset.categoria}`);
                if (filtro === '' || texto.includes(filtro)) {
                    fragment.appendChild(opt.cloneNode(true));
                    const option = document.createElement('option');
                    option.value = etiquetaProducto(opt);
                    option.dataset.id = opt.value;
                    sugerencias.appendChild(option);
                }
            });

            productoSelect.appendChild(fragment);
            productoSuggestions.appendChild(sugerencias);
            sincronizarSelectDesdeBusqueda(productoSearch.value);
            toggleAgregar();
        });

        function toggleAgregar(){
            let opt = productoSelect.selectedOptions[0];
            if (!opt || !opt.value) {
                const encontrado = encontrarOpcionSeleccionada();
                if (encontrado) {
                    productoSelect.value = encontrado.value;
                    opt = encontrado;
                }
            }
            const visible = !!(opt && opt.value);
            agregarWrapper.classList.toggle('hidden', !visible);
            agregarBtn.disabled = !visible;
        }

        productoSelect.addEventListener('change', () => {
            const opt = productoSelect.selectedOptions[0];
            if (!opt || !opt.value) {
                productoInfo.textContent = '';
                toggleAgregar();
                return;
            }
            const stock = (opt.dataset.stock !== '' && opt.dataset.stock != null) ? `Stock: ${opt.dataset.stock}` : 'Stock: —';
            const precio = Number(opt.dataset.precio || 0).toFixed(2).replace('.', ',');
            productoInfo.textContent = `${opt.dataset.categoria} · $ ${precio} · ${stock}`;
            productoSearch.value = etiquetaProducto(opt);
            toggleAgregar();
        });

        function money(n){
            return '$ ' + n.toFixed(2).replace('.', ',');
        }

        function updatePagoUI(){
            const mixto = pagoMixto.checked;
            pagoSimple.classList.toggle('hidden', mixto);
            pagoMixtoCampos.classList.toggle('hidden', !mixto);

            const metodoEfectivoSimple = !mixto && metodoPagoSimple.value === 'efectivo';
            const metodoEfectivoPrimario = mixto && metodoPagoPrimario.value === 'efectivo';
            const metodoEfectivoSecundario = mixto && metodoPagoSecundario.value === 'efectivo';
            efectivoBox.classList.toggle('hidden', !(metodoEfectivoSimple || metodoEfectivoPrimario || metodoEfectivoSecundario));

            if (mixto) {
                if (montoPrimario.value !== '') {
                    ultimoMontoEditado = 'primario';
                    syncMontosMixto('primario');
                } else if (montoSecundario.value !== '') {
                    ultimoMontoEditado = 'secundario';
                    syncMontosMixto('secundario');
                }
            }

            updateVuelto();
        }

        function efectivoAPagar(){
            if (pagoMixto.checked) {
                let totalEfectivo = 0;
                if (metodoPagoPrimario.value === 'efectivo') {
                    totalEfectivo += Number(montoPrimario.value || 0);
                }
                if (metodoPagoSecundario.value === 'efectivo') {
                    totalEfectivo += Number(montoSecundario.value || 0);
                }
                return totalEfectivo;
            }
            return metodoPagoSimple.value === 'efectivo' ? totalActual : 0;
        }

        function updateVuelto(){
            const efectivo = efectivoAPagar();
            const recibido = Number(efectivoRecibido.value || 0);
            const vuelto = Math.max(0, recibido - efectivo);
            vueltoTxt.textContent = money(vuelto);
        }

        function syncMontosMixto(origen){
            if (!pagoMixto.checked || syncingMontos) {
                return;
            }

            syncingMontos = true;
            const total = totalActual;
            const primario = Number(montoPrimario.value || 0);
            const secundario = Number(montoSecundario.value || 0);

            if (total <= 0) {
                syncingMontos = false;
                return;
            }

            if (origen === 'primario') {
                const restante = Math.max(0, total - (Number.isFinite(primario) ? primario : 0));
                montoSecundario.value = restante.toFixed(2);
            } else if (origen === 'secundario') {
                const restante = Math.max(0, total - (Number.isFinite(secundario) ? secundario : 0));
                montoPrimario.value = restante.toFixed(2);
            }

            syncingMontos = false;
            updateVuelto();
        }

        function render(){
            tbody.innerHTML = '';
            if (carrito.length === 0) {
                tbody.appendChild(carritoVacio);
                totalTxt.textContent = '$ 0,00';
                totalActual = 0;
                inputsHidden.innerHTML = '';
                return;
            }

            let total = 0;
            inputsHidden.innerHTML = '';

            carrito.forEach((it, idx) => {
                const subtotal = it.precio * it.cantidad;
                total += subtotal;

                const tr = document.createElement('tr');
                tr.className = 'border-b';
                tr.innerHTML = `
                    <td class="py-2">${it.nombre}</td>
                    <td class="py-2">${money(it.precio)}</td>
                    <td class="py-2">${it.cantidad}</td>
                    <td class="py-2">${money(subtotal)}</td>
                    <td class="py-2 text-right">
                        <button type="button" class="px-3 py-1 bg-red-600 text-white rounded">Quitar</button>
                    </td>
                `;
                tr.querySelector('button').addEventListener('click', () => {
                    carrito.splice(idx, 1);
                    render();
                });

                tbody.appendChild(tr);

                inputsHidden.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="items[${idx}][producto_id]" value="${it.producto_id}">
                    <input type="hidden" name="items[${idx}][cantidad]" value="${it.cantidad}">
                `);
            });

            totalTxt.textContent = money(total);
            totalActual = total;
            updateVuelto();

            if (pagoMixto.checked) {
                if (ultimoMontoEditado === 'primario' && montoPrimario.value !== '') {
                    syncMontosMixto('primario');
                } else if (ultimoMontoEditado === 'secundario' && montoSecundario.value !== '') {
                    syncMontosMixto('secundario');
                }
            }
        }

        function getStock(opt){
            const raw = opt?.dataset?.stock;
            if (raw === undefined || raw === null || raw === '') return null; // sin stock controlable
            const n = Number(raw);
            return Number.isFinite(n) ? n : null;
        }

        function encontrarOpcionSeleccionada(){
            const seleccion = productoSelect.selectedOptions[0];
            if (seleccion && seleccion.value) return seleccion;
            const buscado = normalizar(productoSearch.value.trim());
            if (!buscado) return null;
            const coincidencias = opcionesOriginales.filter((opt) => {
                if (!opt.value) return false;
                const etiqueta = normalizar(etiquetaProducto(opt));
                const nombre = normalizar(opt.dataset.nombre || '');
                return etiqueta.includes(buscado) || nombre.includes(buscado);
            });
            return coincidencias.length === 1 ? coincidencias[0] : null;
        }

        agregarBtn.addEventListener('click', () => {
            const opt = encontrarOpcionSeleccionada();
            if (!opt || !opt.value) { alert('Seleccione un producto.'); return; }

            const producto_id = Number(opt.value);
            const nombre = opt.dataset.nombre;
            const precio = Number(opt.dataset.precio || 0);
            const cantidad = Number(cantidadInput.value || 1);
            const stock = getStock(opt);

            if (!Number.isFinite(cantidad) || cantidad < 1) { alert('Cantidad inválida.'); return; }

            const existente = carrito.find(x => x.producto_id === producto_id);
            const cantActual = existente ? existente.cantidad : 0;
            const nuevaCant = cantActual + cantidad;

            // Fix: evitar 422 por stock insuficiente (control en frontend)
            if (stock !== null && nuevaCant > stock) {
                alert(`Stock insuficiente para: ${nombre}. Disponible: ${stock}`);
                return;
            }

            if (existente) {
                existente.cantidad = nuevaCant;
            } else {
                carrito.push({ producto_id, nombre, precio, cantidad });
            }

            render();
        });

        // Fix: no enviar si no hay items (evita "validation.required")
        ventaForm.addEventListener('submit', (e) => {
            if (carrito.length === 0) {
                e.preventDefault();
                alert('Agregue al menos un producto al carrito.');
                return;
            }

            if (pagoMixto.checked) {
                const monto1 = Number(montoPrimario.value || 0);
                const monto2 = Number(montoSecundario.value || 0);
                const suma = monto1 + monto2;
                if (monto1 <= 0 || monto2 <= 0) {
                    e.preventDefault();
                    alert('Ingrese montos válidos para el pago mixto.');
                    return;
                }
                if (Math.abs(suma - totalActual) > 0.01) {
                    e.preventDefault();
                    alert('La suma de los montos no coincide con el total.');
                    return;
                }
                if (metodoPagoPrimario.value === metodoPagoSecundario.value) {
                    e.preventDefault();
                    alert('Seleccione métodos de pago diferentes.');
                    return;
                }
            }

            const efectivo = efectivoAPagar();
            if (efectivo > 0) {
                const recibido = Number(efectivoRecibido.value || 0);
                if (recibido + 0.01 < efectivo) {
                    e.preventDefault();
                    alert('El efectivo recibido es menor al importe en efectivo.');
                }
            }
        });

        montoPrimario.addEventListener('input', () => {
            ultimoMontoEditado = 'primario';
            syncMontosMixto('primario');
        });

        montoSecundario.addEventListener('input', () => {
            ultimoMontoEditado = 'secundario';
            syncMontosMixto('secundario');
        });

        [pagoMixto, metodoPagoSimple, metodoPagoPrimario, metodoPagoSecundario, montoPrimario, montoSecundario, efectivoRecibido].forEach((el) => {
            el.addEventListener('input', updatePagoUI);
            el.addEventListener('change', updatePagoUI);
        });

        productoSearch.dispatchEvent(new Event('input'));
        updatePagoUI();
        render();
        toggleAgregar();
    </script>
</x-app-layout>
