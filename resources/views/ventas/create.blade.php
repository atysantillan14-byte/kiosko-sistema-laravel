<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="app-title">Nueva venta (Caja)</h2>
                <p class="app-subtitle">Seleccioná productos, cantidades y el método de pago.</p>
            </div>
            <a href="{{ route('ventas.index') }}" class="app-btn-secondary">Volver</a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-4xl">
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                    <div class="font-semibold">Revisá los errores antes de continuar</div>
                    <ul class="mt-2 list-disc pl-5">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="app-card p-6 sm:p-8">
                <form method="POST" action="{{ route('ventas.store') }}" class="space-y-6" id="ventaForm">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="estado" value="confirmada">

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="md:col-span-2">
                            <label class="app-label">Producto</label>
                            <input id="productoSearch" type="search" placeholder="Buscar y seleccionar producto..." class="app-input" autocomplete="off" list="productoSuggestions">
                            <datalist id="productoSuggestions"></datalist>
                            <select id="productoSelect" class="sr-only" aria-hidden="true" hidden>
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
                            <p class="app-helper mt-2" id="productoInfo"></p>
                            <div id="agregarWrapper" class="mt-3 hidden">
                                <button type="button" id="agregarBtn" class="app-btn-primary">
                                    Agregar producto
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="app-label">Cantidad</label>
                            <input id="cantidadInput" type="number" min="0.01" step="0.01" value="1" class="app-input">
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Carrito</h3>
                        <div class="mt-3 overflow-x-auto rounded-2xl border border-slate-200/70">
                            <table class="w-full text-left text-sm" id="tablaCarrito">
                                <thead class="bg-slate-50">
                                    <tr class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        <th class="px-4 py-3">Producto</th>
                                        <th class="px-4 py-3">Precio</th>
                                        <th class="px-4 py-3">Cantidad</th>
                                        <th class="px-4 py-3">Subtotal</th>
                                        <th class="px-4 py-3 text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200/70">
                                    <tr id="carritoVacio">
                                        <td colspan="5" class="px-4 py-4 text-slate-500">No hay productos agregados.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3 md:items-end">
                            <div class="md:col-span-2 space-y-4">
                                <div class="flex items-center gap-2 rounded-xl border border-slate-200/70 bg-slate-50/80 px-3 py-2">
                                    <input type="checkbox" id="pagoMixto" name="pago_mixto" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-200">
                                    <label for="pagoMixto" class="text-sm font-semibold text-slate-700">Pago con dos métodos</label>
                                </div>

                                <div id="pagoSimple">
                                    <label class="app-label">Método de pago</label>
                                    <select name="metodo_pago" id="metodoPagoSimple" class="app-input" required>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="debito">Débito</option>
                                        <option value="credito">Crédito</option>
                                        <option value="transferencia">Transferencia</option>
                                    </select>
                                </div>

                                <div id="pagoMixtoCampos" class="hidden grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="app-label">Método 1</label>
                                        <select name="metodo_pago_primario" id="metodoPagoPrimario" class="app-input">
                                            <option value="efectivo">Efectivo</option>
                                            <option value="debito">Débito</option>
                                            <option value="credito">Crédito</option>
                                            <option value="transferencia">Transferencia</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="app-label">Monto 1</label>
                                        <input type="number" step="0.01" min="0" name="monto_primario" id="montoPrimario" class="app-input" placeholder="0,00">
                                    </div>
                                    <div>
                                        <label class="app-label">Método 2</label>
                                        <select name="metodo_pago_secundario" id="metodoPagoSecundario" class="app-input">
                                            <option value="transferencia">Transferencia</option>
                                            <option value="debito">Débito</option>
                                            <option value="credito">Crédito</option>
                                            <option value="efectivo">Efectivo</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="app-label">Monto 2</label>
                                        <input type="number" step="0.01" min="0" name="monto_secundario" id="montoSecundario" class="app-input" placeholder="0,00">
                                    </div>
                                </div>

                                <div id="efectivoBox" class="hidden">
                                    <label class="app-label">Efectivo recibido</label>
                                    <input type="number" step="0.01" min="0" name="efectivo_recibido" id="efectivoRecibido" class="app-input" placeholder="0,00">
                                    <p class="app-helper mt-1">Vuelto: <span id="vueltoTxt" class="font-semibold text-emerald-600">$ 0,00</span></p>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-200/70 bg-slate-50/80 px-4 py-3 text-right">
                                <div class="text-xs font-semibold text-slate-500">Total</div>
                                <div class="text-2xl font-semibold text-slate-900" id="totalTxt">$ 0,00</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2">
                        <a href="{{ route('ventas.index') }}" class="app-btn-secondary">Cancelar</a>
                        <button type="submit" class="app-btn-primary">Guardar venta</button>
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
            return texto
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[()]/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();
        }

        function etiquetaProducto(opt) {
            const categoria = opt.dataset.categoria || 'Sin categoría';
            return `${opt.dataset.nombre} (${categoria})`;
        }

        function setProductoInfo(texto, esError = false) {
            productoInfo.textContent = texto;
            productoInfo.classList.toggle('text-rose-600', esError);
            productoInfo.classList.toggle('text-slate-500', !esError);
        }

        function obtenerStockSeleccionado(selected) {
            if (!selected) return null;
            if (selected.dataset.stock === undefined || selected.dataset.stock === '') {
                return null;
            }
            const stock = parseFloat(selected.dataset.stock);
            return Number.isNaN(stock) ? null : stock;
        }

        function parseCantidad(valor) {
            const normalizado = (valor || '').toString().replace(',', '.');
            const numero = parseFloat(normalizado);
            if (Number.isNaN(numero) || numero <= 0) {
                return null;
            }
            return Math.round(numero * 100) / 100;
        }

        function formatCantidad(valor) {
            if (valor === null || valor === undefined) return '';
            return valor.toFixed(2).replace('.', ',').replace(/,00$/, '');
        }

        function actualizarProductoSeleccionado() {
            const selected = productoSelect.options[productoSelect.selectedIndex];
            if (!selected || !selected.value) {
                setProductoInfo('');
                agregarWrapper.classList.add('hidden');
                return;
            }

            const stockDisponible = obtenerStockSeleccionado(selected);
            const stockText = stockDisponible !== null ? `Stock: ${formatCantidad(stockDisponible)}` : '';
            const categoria = selected.dataset.categoria || 'Sin categoría';
            const infoBase = `${selected.dataset.nombre} · ${categoria} ${stockText ? '· ' + stockText : ''}`;
            if (stockDisponible !== null && stockDisponible <= 0) {
                setProductoInfo(`${infoBase} · Sin stock`, true);
                agregarWrapper.classList.add('hidden');
                return;
            }
            setProductoInfo(infoBase);
            agregarWrapper.classList.remove('hidden');
        }

        function sincronizarSelectDesdeBusqueda(valor, permitirParcial = false){
            if (!valor) {
                productoSelect.value = '';
                actualizarProductoSeleccionado();
                return false;
            }
            const buscado = normalizar(valor);
            const match = opcionesOriginales.find((opt) => {
                if (!opt.value) return false;
                const etiqueta = normalizar(etiquetaProducto(opt));
                const nombre = normalizar(opt.dataset.nombre || '');
                if (buscado === etiqueta || buscado === nombre) {
                    return true;
                }
                if (!permitirParcial) {
                    return false;
                }
                return etiqueta.includes(buscado) || nombre.includes(buscado);
            });

            if (match) {
                productoSelect.value = match.value;
                actualizarProductoSeleccionado();
                if (!permitirParcial) {
                    productoSearch.value = etiquetaProducto(match);
                    productoSuggestions.innerHTML = '';
                }
                return true;
            }

            productoSelect.value = '';
            actualizarProductoSeleccionado();
            return false;
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
                    sugerencias.appendChild(option);
                }
            });

            productoSelect.appendChild(fragment);
            productoSuggestions.appendChild(sugerencias);

            sincronizarSelectDesdeBusqueda(productoSearch.value.trim(), true);
        });

        productoSearch.addEventListener('change', () => {
            sincronizarSelectDesdeBusqueda(productoSearch.value.trim());
        });

        productoSearch.addEventListener('blur', () => {
            sincronizarSelectDesdeBusqueda(productoSearch.value.trim());
        });

        productoSearch.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });

        cantidadInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });

        productoSelect.addEventListener('change', function() {
            actualizarProductoSeleccionado();
        });

        agregarBtn?.addEventListener('click', () => {
            const selected = productoSelect.options[productoSelect.selectedIndex];
            if (!selected || !selected.value) return;
            const cantidad = parseCantidad(cantidadInput.value) ?? 1;
            const stockDisponible = obtenerStockSeleccionado(selected);

            const item = {
                id: selected.value,
                nombre: selected.dataset.nombre,
                precio: parseFloat(selected.dataset.precio || '0'),
                cantidad: cantidad,
            };

            const existente = carrito.find((producto) => producto.id === item.id);
            const cantidadTotal = (existente?.cantidad || 0) + cantidad;
            if (stockDisponible !== null && cantidadTotal > stockDisponible) {
                setProductoInfo('Stock insuficiente para esa cantidad.', true);
                return;
            }
            if (existente) {
                existente.cantidad += cantidad;
            } else {
                carrito.push(item);
            }

            renderCarrito();
            productoSearch.value = '';
            productoSelect.value = '';
            agregarWrapper.classList.add('hidden');
            productoInfo.textContent = '';
        });

        function renderCarrito() {
            tbody.innerHTML = '';
            if (carrito.length === 0) {
                tbody.appendChild(carritoVacio);
            } else {
                carrito.forEach((producto, index) => {
                    const row = document.createElement('tr');
                    row.classList.add('text-sm');
                    row.innerHTML = `
                        <td class="px-4 py-3 text-slate-700">${producto.nombre}</td>
                        <td class="px-4 py-3 text-slate-700">$ ${producto.precio.toFixed(2).replace('.', ',')}</td>
                        <td class="px-4 py-3 text-slate-700">${formatCantidad(producto.cantidad)}</td>
                        <td class="px-4 py-3 text-slate-700">$ ${(producto.precio * producto.cantidad).toFixed(2).replace('.', ',')}</td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" class="app-btn-ghost px-3 py-1.5 text-xs text-rose-600 hover:text-rose-700" onclick="eliminarProducto(${index})">Quitar</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }

            actualizarTotal();
        }

        function actualizarTotal() {
            totalActual = carrito.reduce((acc, producto) => acc + producto.precio * producto.cantidad, 0);
            totalTxt.textContent = `$ ${totalActual.toFixed(2).replace('.', ',')}`;
            actualizarEfectivoBox();
        }

        function eliminarProducto(index) {
            carrito.splice(index, 1);
            renderCarrito();
        }

        function calcularEfectivoAPagar() {
            if (!pagoMixto.checked) {
                return metodoPagoSimple.value === 'efectivo' ? totalActual : 0;
            }

            const montoPrimarioValor = parseFloat(montoPrimario.value || '0');
            const montoSecundarioValor = parseFloat(montoSecundario.value || '0');
            let efectivo = 0;

            if (metodoPagoPrimario.value === 'efectivo') {
                efectivo += montoPrimarioValor;
            }
            if (metodoPagoSecundario.value === 'efectivo') {
                efectivo += montoSecundarioValor;
            }

            return efectivo;
        }

        function actualizarVuelto() {
            if (efectivoBox.classList.contains('hidden')) {
                return;
            }

            const recibido = parseFloat(efectivoRecibido.value || '0');
            const efectivoAPagar = calcularEfectivoAPagar();
            const vuelto = recibido - efectivoAPagar;
            vueltoTxt.textContent = `$ ${Math.max(vuelto, 0).toFixed(2).replace('.', ',')}`;
        }

        function actualizarEfectivoBox() {
            const efectivoAPagar = calcularEfectivoAPagar();
            const mostrar = efectivoAPagar > 0;
            efectivoBox.classList.toggle('hidden', !mostrar);

            if (!mostrar) {
                efectivoRecibido.value = '';
                vueltoTxt.textContent = '$ 0,00';
            } else {
                actualizarVuelto();
            }
        }

        pagoMixto.addEventListener('change', () => {
            const mix = pagoMixto.checked;
            pagoMixtoCampos.classList.toggle('hidden', !mix);
            pagoSimple.classList.toggle('hidden', mix);
            actualizarEfectivoBox();
        });

        metodoPagoSimple.addEventListener('change', () => {
            actualizarEfectivoBox();
        });

        metodoPagoPrimario.addEventListener('change', actualizarEfectivoBox);
        metodoPagoSecundario.addEventListener('change', actualizarEfectivoBox);

        efectivoRecibido.addEventListener('input', actualizarVuelto);

        function actualizarMontosMixtos() {
            if (syncingMontos) return;
            syncingMontos = true;
            const total = totalActual;
            const primario = parseFloat(montoPrimario.value || '0');
            const secundario = parseFloat(montoSecundario.value || '0');

            if (ultimoMontoEditado === 'primario') {
                montoSecundario.value = Math.max(total - primario, 0).toFixed(2);
            } else {
                montoPrimario.value = Math.max(total - secundario, 0).toFixed(2);
            }

            syncingMontos = false;
        }

        montoPrimario.addEventListener('input', () => {
            ultimoMontoEditado = 'primario';
            actualizarMontosMixtos();
            actualizarEfectivoBox();
        });

        montoSecundario.addEventListener('input', () => {
            ultimoMontoEditado = 'secundario';
            actualizarMontosMixtos();
            actualizarEfectivoBox();
        });

        ventaForm.addEventListener('submit', () => {
            inputsHidden.innerHTML = '';
            carrito.forEach((producto, index) => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = `items[${index}][producto_id]`;
                idInput.value = producto.id;
                inputsHidden.appendChild(idInput);

                const cantidadInputHidden = document.createElement('input');
                cantidadInputHidden.type = 'hidden';
                cantidadInputHidden.name = `items[${index}][cantidad]`;
                cantidadInputHidden.value = producto.cantidad;
                inputsHidden.appendChild(cantidadInputHidden);
            });
        });

        actualizarEfectivoBox();
    </script>
</x-app-layout>
