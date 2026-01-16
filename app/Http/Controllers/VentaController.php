<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $esAdmin = (Auth::user()->role ?? null) === 'admin';
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

        return view('ventas.index', compact('ventas', 'cantidadVentas', 'totalDinero', 'desde', 'hasta', 'buscar', 'esAdmin'));
    }

    public function create()
    {
        $esAdmin = (Auth::user()->role ?? null) === 'admin';
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

        return view('ventas.create', compact('usuarios', 'productos', 'productosForJs', 'esAdmin'));
    }


    public function store(Request $request)
    {
        $esAdmin = (Auth::user()->role ?? null) === 'admin';
        $data = $request->validate([
            'user_id' => $esAdmin ? ['required', 'exists:users,id'] : ['nullable'],
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

        if (! $esAdmin) {
            $data['user_id'] = Auth::id();
        }

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

    public function cierre(Request $request)
    {
        $userId = Auth::id();
        $turno = $request->query('turno');
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');
        $horaDesde = $request->query('hora_desde');
        $horaHasta = $request->query('hora_hasta');

        if ($turno) {
            if ($turno === 'manana') { $horaDesde = '06:00'; $horaHasta = '13:59'; }
            if ($turno === 'tarde') { $horaDesde = '14:00'; $horaHasta = '21:59'; }
            if ($turno === 'noche') { $horaDesde = '22:00'; $horaHasta = '23:59'; }
        }

        $ventasQuery = Venta::query()
            ->with('detalles')
            ->where('estado', '!=', 'anulada')
            ->where('user_id', $userId);

        if ($desde) {
            $ventasQuery->whereDate('created_at', '>=', $desde);
        }
        if ($hasta) {
            $ventasQuery->whereDate('created_at', '<=', $hasta);
        }

        if ($horaDesde && $horaHasta) {
            $ventasQuery->whereRaw("TIME(created_at) BETWEEN ? AND ?", [$horaDesde, $horaHasta]);
        }

        $ventas = (clone $ventasQuery)->get();

        $anuladasQuery = Venta::query()
            ->where('estado', 'anulada')
            ->where('user_id', $userId);

        if ($desde) {
            $anuladasQuery->whereDate('created_at', '>=', $desde);
        }
        if ($hasta) {
            $anuladasQuery->whereDate('created_at', '<=', $hasta);
        }
        if ($horaDesde && $horaHasta) {
            $anuladasQuery->whereRaw("TIME(created_at) BETWEEN ? AND ?", [$horaDesde, $horaHasta]);
        }

        $cantidadVentas = $ventas->count();
        $totalBruto = $ventas->sum(fn ($venta) => $venta->detalles->sum('subtotal'));
        $totalNeto = $ventas->sum('total');
        $totalDescuentos = max(0, $totalBruto - $totalNeto);
        $ticketPromedio = $cantidadVentas > 0 ? $totalNeto / $cantidadVentas : 0;

        $rangos = [
            'desde' => $desde,
            'hasta' => $hasta,
            'hora_desde' => $horaDesde,
            'hora_hasta' => $horaHasta,
            'turno' => $turno,
        ];

        $metodosPago = $ventas->flatMap(function ($venta) {
            $metodos = [];

            if ($venta->metodo_pago === 'mixto') {
                if ($venta->metodo_pago_primario) {
                    $metodos[] = [
                        'metodo' => $venta->metodo_pago_primario,
                        'monto' => (float) $venta->monto_primario,
                    ];
                }
                if ($venta->metodo_pago_secundario) {
                    $metodos[] = [
                        'metodo' => $venta->metodo_pago_secundario,
                        'monto' => (float) $venta->monto_secundario,
                    ];
                }
            } else {
                $metodos[] = [
                    'metodo' => $venta->metodo_pago,
                    'monto' => (float) $venta->total,
                ];
            }

            return $metodos;
        });

        $desglosePagos = $metodosPago
            ->groupBy('metodo')
            ->map(function ($items, $metodo) {
                return [
                    'metodo' => $metodo,
                    'monto' => $items->sum('monto'),
                    'transacciones' => $items->count(),
                ];
            })
            ->sortByDesc('monto')
            ->values();

        $efectivoVentas = $metodosPago
            ->where('metodo', 'efectivo')
            ->sum('monto');

        $efectivoEsperado = $efectivoVentas;

        return view('ventas.cierre', [
            'rangos' => $rangos,
            'rangoInicio' => $ventas->min('created_at'),
            'rangoFin' => $ventas->max('created_at'),
            'cantidadVentas' => $cantidadVentas,
            'totalBruto' => $totalBruto,
            'totalDescuentos' => $totalDescuentos,
            'totalNeto' => $totalNeto,
            'ticketPromedio' => $ticketPromedio,
            'desglosePagos' => $desglosePagos,
            'efectivoVentas' => $efectivoVentas,
            'efectivoEsperado' => $efectivoEsperado,
            'anulacionesCantidad' => $anuladasQuery->count(),
            'anulacionesTotal' => $anuladasQuery->sum('total'),
        ]);
    }

    public function edit(Venta $venta)
    {
        $this->authorizeVentaEdicion($venta);
        $esAdmin = (Auth::user()->role ?? null) === 'admin';
        $usuarios = User::query()->orderBy('name')->get(['id', 'name']);
        return view('ventas.edit', compact('venta', 'usuarios', 'esAdmin'));
    }

    public function update(Request $request, Venta $venta)
    {
        $this->authorizeVentaEdicion($venta);
        $esAdmin = (Auth::user()->role ?? null) === 'admin';
        $rules = [
            'metodo_pago' => ['required', 'string', 'max:50'],
            'estado' => ['required', 'string', 'max:40'],
        ];

        if ($esAdmin) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        $data = $request->validate($rules);

        if (! $esAdmin) {
            $data['user_id'] = $venta->user_id;
        }

        $venta->update($data);

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada.');
    }

    public function destroy(Venta $venta)
    {
        $this->authorizeVentaEdicion($venta);
        $venta->delete();
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada.');
    }

    private function authorizeVentaEdicion(Venta $venta): void
    {
        $esAdmin = (Auth::user()->role ?? null) === 'admin';

        if ($esAdmin) {
            return;
        }

        if ($venta->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
