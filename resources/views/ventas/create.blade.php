<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Nueva venta (Caja)</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('ventas.store') }}" class="bg-white shadow-sm sm:rounded-lg p-6" id="ventaForm">
            @csrf

            {{-- Fix: tu store() requiere user_id y estado --}}
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <input type="hidden" name="estado" value="confirmada">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Producto</label>
                    <select id="productoSelect" class="w-full rounded border-gray-300">
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
                    <p class="text-xs text-gray-500 mt-1" id="productoInfo"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Cantidad</label>
                    <input id="cantidadInput" type="number" min="1" value="1" class="w-full rounded border-gray-300">
                </div>
            </div>

            <button type="button" id="agregarBtn"
                    class="px-4 py-2 bg-gray-900 text-white rounded hover:bg-black">
                Agregar
            </button>

            <div class="mt-6">
                <h3 class="font-semibold mb-2">Carrito</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="tablaCarrito">
                        <thead>
                        <tr class="border-b">
                            <th class="py-2">Producto</th>
                            <th class="py-2">Precio</th>
                            <th class="py-2">Cantidad</th>
                            <th class="py-2">Subtotal</th>
                            <th class="py-2 text-right">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr id="carritoVacio">
                            <td colspan="5" class="py-3 text-gray-500">No hay productos agregados.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div class="w-64">
                        <label class="block text-sm font-medium mb-1">Método de pago</label>
                        <select name="metodo_pago" class="w-full rounded border-gray-300" required>
                            <option value="efectivo">Efectivo</option>
                            <option value="debito">Débito</option>
                            <option value="credito">Crédito</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>

                    <div class="text-right">
                        <div class="text-sm text-gray-600">Total</div>
                        <div class="text-2xl font-bold" id="totalTxt">$ 0,00</div>
                    </div>
                </div>

                <div class="mt-6 flex gap-2">
                    <a href="{{ route('ventas.index') }}" class="px-4 py-2 border rounded">Volver</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Guardar venta
                    </button>
                </div>
            </div>

            <div id="inputsHidden"></div>
        </form>
    </div>

    <script>
        const productoSelect = document.getElementById('productoSelect');
        const cantidadInput = document.getElementById('cantidadInput');
        const agregarBtn = document.getElementById('agregarBtn');
        const carritoVacio = document.getElementById('carritoVacio');
        const tbody = document.querySelector('#tablaCarrito tbody');
        const totalTxt = document.getElementById('totalTxt');
        const inputsHidden = document.getElementById('inputsHidden');
        const productoInfo = document.getElementById('productoInfo');
        const ventaForm = document.getElementById('ventaForm');

        let carrito = [];

        productoSelect.addEventListener('change', () => {
            const opt = productoSelect.selectedOptions[0];
            if (!opt || !opt.value) { productoInfo.textContent = ''; return; }
            const stock = (opt.dataset.stock !== '' && opt.dataset.stock != null) ? `Stock: ${opt.dataset.stock}` : 'Stock: —';
            const precio = Number(opt.dataset.precio || 0).toFixed(2).replace('.', ',');
            productoInfo.textContent = `${opt.dataset.categoria} · $ ${precio} · ${stock}`;
        });

        function money(n){
            return '$ ' + n.toFixed(2).replace('.', ',');
        }

        function render(){
            tbody.innerHTML = '';
            if (carrito.length === 0) {
                tbody.appendChild(carritoVacio);
                totalTxt.textContent = '$ 0,00';
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
        }

        function getStock(opt){
            const raw = opt?.dataset?.stock;
            if (raw === undefined || raw === null || raw === '') return null; // sin stock controlable
            const n = Number(raw);
            return Number.isFinite(n) ? n : null;
        }

        agregarBtn.addEventListener('click', () => {
            const opt = productoSelect.selectedOptions[0];
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
            }
        });

        render();
    </script>
</x-app-layout>

