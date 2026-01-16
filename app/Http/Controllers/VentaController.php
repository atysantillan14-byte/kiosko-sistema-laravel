<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');
        $buscar = trim((string) $request->query('q', ''));
        $buscarId = null;

        if ($buscar !== '' && preg_match('/^#?\s*(\d+)\s*$/', $buscar, $matches)) {
            $buscarId = (int) $matches[1];
        }

        $ventasQuery = Venta::query()
            ->with('usuario')
            ->where('estado', '!=', 'anulada');

        if ($desde) {
            $ventasQuery->whereDate('created_at', '>=', $desde);
        }
        if ($hasta) {
            $ventasQuery->whereDate('created_at', '<=', $hasta);
        }

        if ($buscar !== '') {
            $ventasQuery->where(function ($q) use ($buscar, $buscarId) {
                if ($buscarId !== null) {
                    $q->where('id', $buscarId);
                } else {
                    $q->where('id', $buscar);
                }

                $q->orWhere('metodo_pago', 'like', "%{$buscar}%")
                  ->orWhere('estado', 'like', "%{$buscar}%")
                  ->orWhereHas('usuario', fn($u) => $u->where('name', 'like', "%{$buscar}%"));
            });
        }

        $cantidadVentas = (clone $ventasQuery)->count();
        $totalDinero    = (clone $ventasQuery)->sum('total');

        $ventas = $ventasQuery
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('ventas.index', compact('ventas', 'cantidadVentas', 'totalDinero', 'desde', 'hasta', 'buscar'));
    }

    public function create()
    {
        $usuarios = User::orderBy('name')->get(['id', 'name']);

        $productos = Producto::where('disponible', 1)
            ->with('categoria')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'precio', 'precio_descuento', 'stock', 'categoria_id']);

        $productosForJs = $productos->map(function ($p) {
            return [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'precio' => (float) $p->precio,
                'stock' => (int) $p->stock,
                'categoria' => $p->categoria?->nombre,
            ];
        })->values();

        return view('ventas.create', compact('usuarios', 'productos', 'productosForJs'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'metodo_pago' => ['nullable', 'string', 'max:50'],
            'pago_mixto' => ['nullable', 'boolean'],
            'metodo_pago_primario' => ['nullable', 'string', 'max:50'],
            'metodo_pago_secundario' => ['nullable', 'string', 'max:50'],
            'monto_primario' => ['nullable', 'numeric', 'min:0.01'],
            'monto_secundario' => ['nullable', 'numeric', 'min:0.01'],
            'efectivo_recibido' => ['nullable', 'numeric', 'min:0'],
            'estado' => ['required', 'string', 'max:40'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'exists:productos,id'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($data) {
            $pagoMixto = (bool) ($data['pago_mixto'] ?? false);
            $metodoPrimario = $pagoMixto ? ($data['metodo_pago_primario'] ?? null) : ($data['metodo_pago'] ?? null);
            $metodoSecundario = $pagoMixto ? ($data['metodo_pago_secundario'] ?? null) : null;
            $montoPrimario = $pagoMixto ? (float) ($data['monto_primario'] ?? 0) : null;
            $montoSecundario = $pagoMixto ? (float) ($data['monto_secundario'] ?? 0) : null;
            $efectivoRecibido = array_key_exists('efectivo_recibido', $data) ? (float) $data['efectivo_recibido'] : null;

            if (!$metodoPrimario) {
                abort(422, 'Seleccione un método de pago.');
            }

            if ($pagoMixto) {
                if (!$metodoSecundario) {
                    abort(422, 'Seleccione el segundo método de pago.');
                }
                if ($metodoPrimario === $metodoSecundario) {
                    abort(422, 'Los métodos de pago deben ser diferentes.');
                }
                if ($montoPrimario <= 0 || $montoSecundario <= 0) {
                    abort(422, 'Ingrese montos válidos para el pago mixto.');
                }
            }

            $venta = Venta::create([
                'user_id' => $data['user_id'],
                'metodo_pago' => $pagoMixto ? 'mixto' : $metodoPrimario,
                'metodo_pago_primario' => $metodoPrimario,
                'metodo_pago_secundario' => $metodoSecundario,
                'monto_primario' => $montoPrimario,
                'monto_secundario' => $montoSecundario,
                'efectivo_recibido' => $efectivoRecibido,
                'estado' => $data['estado'],
                'total' => 0,
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $producto = Producto::lockForUpdate()->find($item['producto_id']);
                $cantidad = (int) $item['cantidad'];

                $precio = (float) $producto->precio;
                $subtotal = $precio * $cantidad;

                // Descontar stock (opcional: si querés permitir stock negativo, avisame)
                if ($producto->stock < $cantidad) {
                    abort(422, "Stock insuficiente para: {$producto->nombre}");
                }
                $producto->decrement('stock', $cantidad);

                // Necesitás la tabla detalles_venta para esto
                $venta->detalles()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $tolerancia = 0.01;
            if ($pagoMixto) {
                $suma = $montoPrimario + $montoSecundario;
                if (abs($suma - $total) > $tolerancia) {
                    abort(422, 'La suma de los montos no coincide con el total.');
                }
            }

            $efectivoAPagar = 0.0;
            if ($metodoPrimario === 'efectivo') {
                $efectivoAPagar += $pagoMixto ? $montoPrimario : $total;
            }
            if ($metodoSecundario === 'efectivo') {
                $efectivoAPagar += $montoSecundario;
            }

            $cambio = null;
            if ($efectivoAPagar > 0) {
                if ($efectivoRecibido === null) {
                    abort(422, 'Ingrese el efectivo recibido.');
                }
                if ($efectivoRecibido + $tolerancia < $efectivoAPagar) {
                    abort(422, 'El efectivo recibido es menor al importe en efectivo.');
                }
                $cambio = max(0, $efectivoRecibido - $efectivoAPagar);
            }

            $venta->update([
                'total' => $total,
                'efectivo_cambio' => $cambio,
            ]);

            return redirect()->route('ventas.index')->with('success', 'Venta creada.');
        });
    }

    public function show(Venta $venta)
    {
        $venta->load(['usuario', 'detalles.producto']);
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $usuarios = User::query()->orderBy('name')->get(['id', 'name']);
        return view('ventas.edit', compact('venta', 'usuarios'));
    }

    public function update(Request $request, Venta $venta)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'metodo_pago' => ['required', 'string', 'max:50'],
            'estado' => ['required', 'string', 'max:40'],
        ]);

        $venta->update($data);

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada.');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada.');
    }
}
