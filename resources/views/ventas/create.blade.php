<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Nueva venta</h2>
            <p class="text-sm text-slate-500">Registrá productos, método de pago y total.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <x-card>
            <form method="POST" action="{{ route('ventas.store') }}" class="space-y-6" id="ventaForm">
                @csrf

                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <input type="hidden" name="estado" value="confirmada">

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <div class="lg:col-span-2 space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Producto</label>
                        <div class="flex flex-col gap-2">
                            <input
                                id="productoSearch"
                                type="search"
                                placeholder="Buscar producto por nombre o categoría..."
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                                autocomplete="off"
                                list="productoSuggestions"
                            >
                            <datalist id="productoSuggestions"></datalist>
                            <select id="productoSelect" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
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
                        </div>
                        <p class="text-xs text-slate-500" id="productoInfo"></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-slate-700" for="cantidadInput">Cantidad</label>
                        <input id="cantidadInput" type="number" min="1" value="1" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                    </div>
                </div>

                <div>
                    <x-button type="button" id="agregarBtn">Agregar</x-button>
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-slate-900">Carrito</h3>
                    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                        <table class="w-full text-sm" id="tablaCarrito">
                            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                                <tr>
                                    <th class="px-4 py-3">Producto</th>
                                    <th class="px-4 py-3">Precio</th>
                                    <th class="px-4 py-3">Cantidad</th>
                                    <th class="px-4 py-3">Subtotal</th>
                                    <th class="px-4 py-3 text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr id="carritoVacio">
                                    <td colspan="5" class="px-4 py-4 text-slate-500">No hay productos agregados.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:items-end">
                        <div class="md:col-span-2 space-y-4">
                            <x-checkbox name="pago_mixto" id="pagoMixto" label="Pago con dos métodos" value="1" />

                            <div id="pagoSimple">
                                <x-select name="metodo_pago" id="metodoPagoSimple" label="Método de pago" required>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="debito">Débito</option>
                                    <option value="credito">Crédito</option>
                                    <option value="transferencia">Transferencia</option>
                                </x-select>
                            </div>

                            <div id="pagoMixtoCampos" class="hidden grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <x-select name="metodo_pago_primario" id="metodoPagoPrimario" label="Método 1">
                                        <option value="efectivo">Efectivo</option>
                                        <option value="debito">Débito</option>
                                        <option value="credito">Crédito</option>
                                        <option value="transferencia">Transferencia</option>
                                    </x-select>
                                </div>
                                <x-input name="monto_primario" id="montoPrimario" type="number" step="0.01" min="0" label="Monto 1" placeholder="0,00" />
                                <div>
                                    <x-select name="metodo_pago_secundario" id="metodoPagoSecundario" label="Método 2">
                                        <option value="transferencia">Transferencia</option>
                                        <option value="debito">Débito</option>
                                        <option value="credito">Crédito</option>
                                        <option value="efectivo">Efectivo</option>
                                    </x-select>
                                </div>
                                <x-input name="monto_secundario" id="montoSecundario" type="number" step="0.01" min="0" label="Monto 2" placeholder="0,00" />
                            </div>

                            <div id="efectivoBox" class="hidden">
                                <x-input name="efectivo_recibido" id="efectivoRecibido" type="number" step="0.01" min="0" label="Efectivo recibido" placeholder="0,00" />
                                <p class="text-xs text-slate-500 mt-1">Vuelto: <span id="vueltoTxt">$ 0,00</span></p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-right dark:border-slate-800 dark:bg-slate-800/60">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Total</div>
                            <div class="text-2xl font-bold text-slate-900" id="totalTxt">$ 0,00</div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <x-button variant="outline" as="a" href="{{ route('ventas.index') }}">Volver</x-button>
                        <x-button type="submit">Guardar venta</x-button>
                    </div>
                </div>

                <div id="inputsHidden"></div>
            </form>
        </x-card>
    </div>

    <script>
        const productoSearch = document.getElementById('productoSearch');
        const productoSelect = document.getElementById('productoSelect');
        const cantidadInput = document.getElementById('cantidadInput');
        const agregarBtn = document.getElementById('agregarBtn');
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
                mostrarInfoProducto(match);
            }
        }

        function actualizarSugerencias(valor){
            productoSuggestions.innerHTML = '';
            if (!valor) return;
            const buscado = normalizar(valor);
            opcionesOriginales.forEach((opt) => {
                if (!opt.value) return;
                const etiqueta = etiquetaProducto(opt);
                if (normalizar(etiqueta).includes(buscado)) {
                    const option = document.createElement('option');
                    option.value = etiqueta;
                    productoSuggestions.appendChild(option);
                }
            });
        }

        function mostrarInfoProducto(opt){
            if (!opt || !opt.value) {
                productoInfo.textContent = '';
                return;
            }
            const stock = opt.dataset.stock;
            const precio = opt.dataset.precio;
            productoInfo.textContent = `Precio: $ ${Number(precio).toFixed(2)} · Stock: ${stock}`;
        }

        productoSearch.addEventListener('input', (e) => {
            actualizarSugerencias(e.target.value);
        });

        productoSearch.addEventListener('change', (e) => {
            sincronizarSelectDesdeBusqueda(e.target.value);
        });

        productoSelect.addEventListener('change', () => {
            const selected = productoSelect.options[productoSelect.selectedIndex];
            if (selected && selected.value) {
                productoSearch.value = etiquetaProducto(selected);
            }
            mostrarInfoProducto(selected);
        });

        agregarBtn.addEventListener('click', () => {
            const selected = productoSelect.options[productoSelect.selectedIndex];
            if (!selected || !selected.value) return;

            const id = selected.value;
            const nombre = selected.dataset.nombre;
            const precio = parseFloat(selected.dataset.precio || 0);
            const cantidad = parseInt(cantidadInput.value || 1, 10);

            if (!cantidad || cantidad <= 0) return;

            const existente = carrito.find((item) => item.id === id);
            if (existente) {
                existente.cantidad += cantidad;
            } else {
                carrito.push({ id, nombre, precio, cantidad });
            }

            cantidadInput.value = 1;
            renderCarrito();
        });

        function renderCarrito(){
            tbody.innerHTML = '';
            inputsHidden.innerHTML = '';

            if (carrito.length === 0) {
                carritoVacio.style.display = '';
                tbody.appendChild(carritoVacio);
                totalActual = 0;
                actualizarTotal();
                return;
            }

            carritoVacio.style.display = 'none';
            totalActual = 0;

            carrito.forEach((item, index) => {
                const subtotal = item.precio * item.cantidad;
                totalActual += subtotal;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-4 py-3 font-medium text-slate-900">${item.nombre}</td>
                    <td class="px-4 py-3 text-slate-600">$ ${item.precio.toFixed(2)}</td>
                    <td class="px-4 py-3 text-slate-600">${item.cantidad}</td>
                    <td class="px-4 py-3 font-semibold text-slate-900">$ ${subtotal.toFixed(2)}</td>
                    <td class="px-4 py-3 text-right">
                        <button type="button" class="text-sm font-semibold text-rose-600" data-index="${index}">Quitar</button>
                    </td>
                `;
                tbody.appendChild(tr);

                tr.querySelector('button').addEventListener('click', (e) => {
                    const idx = parseInt(e.target.dataset.index, 10);
                    carrito.splice(idx, 1);
                    renderCarrito();
                });

                inputsHidden.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="items[${index}][producto_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][cantidad]" value="${item.cantidad}">
                    <input type="hidden" name="items[${index}][precio]" value="${item.precio}">
                `);
            });

            actualizarTotal();
        }

        function actualizarTotal(){
            totalTxt.textContent = `$ ${totalActual.toFixed(2)}`;
            calcularVuelto();
        }

        function alternarPagoMixto(){
            if (pagoMixto.checked) {
                pagoSimple.classList.add('hidden');
                pagoMixtoCampos.classList.remove('hidden');
                metodoPagoSimple.removeAttribute('required');
            } else {
                pagoSimple.classList.remove('hidden');
                pagoMixtoCampos.classList.add('hidden');
                metodoPagoSimple.setAttribute('required', 'required');
            }
        }

        function alternarEfectivo(){
            const metodo = pagoMixto.checked ? metodoPagoPrimario.value : metodoPagoSimple.value;
            if (metodo === 'efectivo') {
                efectivoBox.classList.remove('hidden');
            } else {
                efectivoBox.classList.add('hidden');
                efectivoRecibido.value = '';
                vueltoTxt.textContent = '$ 0,00';
            }
        }

        function calcularVuelto(){
            if (efectivoBox.classList.contains('hidden')) return;
            const recibido = parseFloat(efectivoRecibido.value || 0);
            const vuelto = recibido - totalActual;
            vueltoTxt.textContent = `$ ${vuelto.toFixed(2)}`;
        }

        function sincronizarMontos(){
            if (!pagoMixto.checked) return;
            if (syncingMontos) return;
            syncingMontos = true;

            const total = totalActual;
            if (ultimoMontoEditado === 'primario') {
                const primary = parseFloat(montoPrimario.value || 0);
                montoSecundario.value = (total - primary).toFixed(2);
            } else {
                const secondary = parseFloat(montoSecundario.value || 0);
                montoPrimario.value = (total - secondary).toFixed(2);
            }

            syncingMontos = false;
        }

        pagoMixto.addEventListener('change', () => {
            alternarPagoMixto();
            alternarEfectivo();
        });

        metodoPagoSimple.addEventListener('change', alternarEfectivo);
        metodoPagoPrimario.addEventListener('change', alternarEfectivo);
        efectivoRecibido.addEventListener('input', calcularVuelto);

        montoPrimario.addEventListener('input', () => {
            ultimoMontoEditado = 'primario';
            sincronizarMontos();
        });
        montoSecundario.addEventListener('input', () => {
            ultimoMontoEditado = 'secundario';
            sincronizarMontos();
        });

        ventaForm.addEventListener('submit', (e) => {
            if (carrito.length === 0) {
                e.preventDefault();
                alert('Agregá al menos un producto.');
                return;
            }

            if (pagoMixto.checked) {
                const totalMixto = (parseFloat(montoPrimario.value || 0) + parseFloat(montoSecundario.value || 0)).toFixed(2);
                if (parseFloat(totalMixto) !== parseFloat(totalActual.toFixed(2))) {
                    e.preventDefault();
                    alert('La suma de los montos no coincide con el total.');
                }
            }
        });

        alternarPagoMixto();
        alternarEfectivo();
    </script>
</x-app-layout>
