<?php

namespace App\Http\Controllers;

use App\Models\CierreCaja;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        $rangoInicio = $desde ? Carbon::parse($desde)->startOfDay() : null;
        $rangoFin = $hasta ? Carbon::parse($hasta)->startOfDay() : null;

        if (! $rangoInicio) {
            $rangoInicio = (clone $ventasQuery)->min('created_at');
            $rangoInicio = $rangoInicio ? Carbon::parse($rangoInicio) : null;
        }

        if (! $rangoFin) {
            $rangoFin = (clone $ventasQuery)->max('created_at');
            $rangoFin = $rangoFin ? Carbon::parse($rangoFin) : null;
        }

        $diasPromedio = 0;
        if ($rangoInicio && $rangoFin) {
            $diasPromedio = $rangoInicio->diffInDays($rangoFin) + 1;
        }
        $promedioDiario = $diasPromedio > 0 ? $totalDinero / $diasPromedio : 0;

        $ventas = $ventasQuery
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('ventas.index', compact(
            'ventas',
            'cantidadVentas',
            'totalDinero',
            'promedioDiario',
            'desde',
            'hasta',
            'buscar',
            'esAdmin'
        ));
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
                'stock' => (float) $p->stock,
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
            'items.*.cantidad' => ['required', 'numeric', 'min:0.01'],
        ]);

        if (! $esAdmin) {
            $data['user_id'] = Auth::id();
        }

        return DB::transaction(function () use ($data) {
            $pagoMixto = (bool) ($data['pago_mixto'] ?? false);
            $metodoPrimario = $this->normalizeMetodoPago($pagoMixto ? ($data['metodo_pago_primario'] ?? null) : ($data['metodo_pago'] ?? null));
            $metodoSecundario = $this->normalizeMetodoPago($pagoMixto ? ($data['metodo_pago_secundario'] ?? null) : null);
            $montoPrimario = $pagoMixto ? (float) ($data['monto_primario'] ?? 0) : null;
            $montoSecundario = $pagoMixto ? (float) ($data['monto_secundario'] ?? 0) : null;
            $efectivoRecibido = array_key_exists('efectivo_recibido', $data) ? (float) $data['efectivo_recibido'] : null;

            if (! $metodoPrimario) {
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
                $cantidadTexto = str_replace(',', '.', (string) $item['cantidad']);
                $cantidad = round((float) $cantidadTexto, 2);
                $cantidadNormalizada = number_format($cantidad, 2, '.', '');

                $precio = round((float) $producto->precio, 2);
                $subtotal = round($precio * $cantidad, 2);
                $subtotalNormalizado = number_format($subtotal, 2, '.', '');

                // Descontar stock (opcional: si querés permitir stock negativo, avisame)
                $stockActual = (float) $producto->stock;
                $stockFinal = round($stockActual - $cantidad, 2);
                if ($stockFinal < 0) {
                    abort(422, "Stock insuficiente para: {$producto->nombre}");
                }
                $producto->update([
                    'stock' => number_format($stockFinal, 2, '.', ''),
                ]);

                // Necesitás la tabla detalles_venta para esto
                $venta->detalles()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidadNormalizada,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotalNormalizado,
                ]);

                $total = round($total + $subtotal, 2);
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
        $esAdmin = (Auth::user()->role ?? null) === 'admin';
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
            ->with('detalles.producto')
            ->where('estado', '!=', 'anulada');

        if (! $esAdmin) {
            $ventasQuery->where('user_id', $userId);
        }

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
            ->where('estado', 'anulada');

        if (! $esAdmin) {
            $anuladasQuery->where('user_id', $userId);
        }

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
        $rangoInicio = $ventas->min('created_at');
        $rangoFin = $ventas->max('created_at');
        $diasPromedio = 0;

        if ($rangoInicio && $rangoFin) {
            $diasPromedio = Carbon::parse($rangoInicio)->diffInDays(Carbon::parse($rangoFin)) + 1;
        }
        $promedioDiario = $diasPromedio > 0 ? $totalNeto / $diasPromedio : 0;

        $rangos = [
            'desde' => $desde,
            'hasta' => $hasta,
            'hora_desde' => $horaDesde,
            'hora_hasta' => $horaHasta,
            'turno' => $turno,
        ];

        $metodosPago = $ventas->flatMap(function ($venta) {
            $metodos = [];
            $metodoVenta = $this->normalizeMetodoPago($venta->metodo_pago);

            if ($metodoVenta === 'mixto') {
                $metodoPrimario = $this->normalizeMetodoPago($venta->metodo_pago_primario);
                if ($metodoPrimario) {
                    $metodos[] = [
                        'metodo' => $metodoPrimario,
                        'monto' => (float) $venta->monto_primario,
                    ];
                }
                $metodoSecundario = $this->normalizeMetodoPago($venta->metodo_pago_secundario);
                if ($metodoSecundario) {
                    $metodos[] = [
                        'metodo' => $metodoSecundario,
                        'monto' => (float) $venta->monto_secundario,
                    ];
                }
            } elseif ($metodoVenta) {
                $metodos[] = [
                    'metodo' => $metodoVenta,
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

        $productosVendidos = $ventas
            ->flatMap(fn ($venta) => $venta->detalles)
            ->groupBy('producto_id')
            ->map(function ($items) {
                $producto = $items->first()->producto;

                return [
                    'producto' => $producto?->nombre ?? 'Producto sin nombre',
                    'cantidad' => $items->sum('cantidad'),
                    'total' => $items->sum('subtotal'),
                ];
            })
            ->sortByDesc('total')
            ->values();

        return view('ventas.cierre', [
            'rangos' => $rangos,
            'rangoInicio' => $rangoInicio,
            'rangoFin' => $rangoFin,
            'cantidadVentas' => $cantidadVentas,
            'totalBruto' => $totalBruto,
            'totalDescuentos' => $totalDescuentos,
            'totalNeto' => $totalNeto,
            'ticketPromedio' => $ticketPromedio,
            'promedioDiario' => $promedioDiario,
            'desglosePagos' => $desglosePagos,
            'efectivoVentas' => $efectivoVentas,
            'efectivoEsperado' => $efectivoEsperado,
            'productosVendidos' => $productosVendidos,
            'anulacionesCantidad' => $anuladasQuery->count(),
            'anulacionesTotal' => $anuladasQuery->sum('total'),
        ]);
    }

    public function guardarCierre(Request $request)
    {
        $esAdmin = (Auth::user()->role ?? null) === 'admin';
        $userId = Auth::id();
        $turno = $request->input('turno');
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        $horaDesde = $request->input('hora_desde');
        $horaHasta = $request->input('hora_hasta');

        $data = $request->validate([
            'fondo_inicial' => ['nullable', 'numeric'],
            'ingresos' => ['nullable', 'numeric'],
            'retiros' => ['nullable', 'numeric'],
            'devoluciones' => ['nullable', 'numeric'],
            'efectivo_contado' => ['nullable', 'numeric'],
            'diferencia' => ['nullable', 'numeric'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($turno) {
            if ($turno === 'manana') { $horaDesde = '06:00'; $horaHasta = '13:59'; }
            if ($turno === 'tarde') { $horaDesde = '14:00'; $horaHasta = '21:59'; }
            if ($turno === 'noche') { $horaDesde = '22:00'; $horaHasta = '23:59'; }
        }

        $ventasQuery = Venta::query()
            ->with('detalles.producto')
            ->where('estado', '!=', 'anulada');

        if (! $esAdmin) {
            $ventasQuery->where('user_id', $userId);
        }

        if ($desde) {
            $ventasQuery->whereDate('created_at', '>=', $desde);
        }
        if ($hasta) {
            $ventasQuery->whereDate('created_at', '<=', $hasta);
        }
        if ($horaDesde && $horaHasta) {
            $ventasQuery->whereRaw("TIME(created_at) BETWEEN ? AND ?", [$horaDesde, $horaHasta]);
        }

        $ventas = $ventasQuery->get();

        $cantidadVentas = $ventas->count();
        $totalBruto = $ventas->sum(fn ($venta) => $venta->detalles->sum('subtotal'));
        $totalNeto = $ventas->sum('total');
        $totalDescuentos = max(0, $totalBruto - $totalNeto);
        $ticketPromedio = $cantidadVentas > 0 ? $totalNeto / $cantidadVentas : 0;

        $metodosPago = $ventas->flatMap(function ($venta) {
            $metodos = [];
            $metodoVenta = $this->normalizeMetodoPago($venta->metodo_pago);

            if ($metodoVenta === 'mixto') {
                $metodoPrimario = $this->normalizeMetodoPago($venta->metodo_pago_primario);
                if ($metodoPrimario) {
                    $metodos[] = [
                        'metodo' => $metodoPrimario,
                        'monto' => (float) $venta->monto_primario,
                    ];
                }
                $metodoSecundario = $this->normalizeMetodoPago($venta->metodo_pago_secundario);
                if ($metodoSecundario) {
                    $metodos[] = [
                        'metodo' => $metodoSecundario,
                        'monto' => (float) $venta->monto_secundario,
                    ];
                }
            } elseif ($metodoVenta) {
                $metodos[] = [
                    'metodo' => $metodoVenta,
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
            ->values()
            ->toArray();

        $efectivoVentas = $metodosPago
            ->where('metodo', 'efectivo')
            ->sum('monto');

        $efectivoEsperado = $efectivoVentas
            + ($data['fondo_inicial'] ?? 0)
            + ($data['ingresos'] ?? 0)
            - ($data['retiros'] ?? 0)
            - ($data['devoluciones'] ?? 0);

        $productosVendidos = $ventas
            ->flatMap(fn ($venta) => $venta->detalles)
            ->groupBy('producto_id')
            ->map(function ($items) {
                $producto = $items->first()->producto;

                return [
                    'producto' => $producto?->nombre ?? 'Producto sin nombre',
                    'cantidad' => $items->sum('cantidad'),
                    'total' => $items->sum('subtotal'),
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->toArray();

        $this->ensureCierresCajaTableExists();

        CierreCaja::create([
            'user_id' => $userId,
            'desde' => $desde,
            'hasta' => $hasta,
            'hora_desde' => $horaDesde,
            'hora_hasta' => $horaHasta,
            'turno' => $turno,
            'total_bruto' => $totalBruto,
            'total_neto' => $totalNeto,
            'total_descuentos' => $totalDescuentos,
            'cantidad_ventas' => $cantidadVentas,
            'ticket_promedio' => $ticketPromedio,
            'efectivo_ventas' => $efectivoVentas,
            'efectivo_esperado' => $efectivoEsperado,
            'efectivo_contado' => $data['efectivo_contado'] ?? 0,
            'diferencia' => $data['diferencia'] ?? 0,
            'fondo_inicial' => $data['fondo_inicial'] ?? 0,
            'ingresos' => $data['ingresos'] ?? 0,
            'retiros' => $data['retiros'] ?? 0,
            'devoluciones' => $data['devoluciones'] ?? 0,
            'observaciones' => $data['observaciones'] ?? null,
            'desglose_pagos' => $desglosePagos,
            'productos' => $productosVendidos,
        ]);

        $redirectParams = array_filter([
            'desde' => $desde,
            'hasta' => $hasta,
            'hora_desde' => $horaDesde,
            'hora_hasta' => $horaHasta,
            'turno' => $turno,
        ], fn ($value) => $value !== null && $value !== '');

        return redirect()
            ->route('ventas.cierre', $redirectParams)
            ->with('success', 'Cierre de caja guardado.');
    }

    public function cierresIndex(Request $request)
    {
        $esAdmin = (Auth::user()->role ?? null) === 'admin';

        $cierresQuery = CierreCaja::query()
            ->with('usuario')
            ->orderByDesc('created_at');

        if (! $esAdmin) {
            $cierresQuery->where('user_id', Auth::id());
        }

        $cierres = $cierresQuery->paginate(12)->withQueryString();

        return view('ventas.cierres.index', compact('cierres', 'esAdmin'));
    }

    public function cierresShow(CierreCaja $cierreCaja)
    {
        $this->authorizeCierreAcceso($cierreCaja);

        $cierreCaja->load('usuario');
        $desglosePagos = collect($cierreCaja->desglose_pagos ?? []);
        $productos = collect($cierreCaja->productos ?? []);

        return view('ventas.cierres.show', [
            'cierre' => $cierreCaja,
            'desglosePagos' => $desglosePagos,
            'productos' => $productos,
            'autoPrint' => request()->boolean('imprimir'),
        ]);
    }

    private function ensureCierresCajaTableExists(): void
    {
        if (Schema::hasTable('cierres_caja')) {
            return;
        }

        Schema::create('cierres_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('desde')->nullable();
            $table->date('hasta')->nullable();
            $table->time('hora_desde')->nullable();
            $table->time('hora_hasta')->nullable();
            $table->string('turno', 20)->nullable();
            $table->decimal('total_bruto', 12, 2)->default(0);
            $table->decimal('total_neto', 12, 2)->default(0);
            $table->decimal('total_descuentos', 12, 2)->default(0);
            $table->unsignedInteger('cantidad_ventas')->default(0);
            $table->decimal('ticket_promedio', 12, 2)->default(0);
            $table->decimal('efectivo_ventas', 12, 2)->default(0);
            $table->decimal('efectivo_esperado', 12, 2)->default(0);
            $table->decimal('efectivo_contado', 12, 2)->default(0);
            $table->decimal('diferencia', 12, 2)->default(0);
            $table->decimal('fondo_inicial', 12, 2)->default(0);
            $table->decimal('ingresos', 12, 2)->default(0);
            $table->decimal('retiros', 12, 2)->default(0);
            $table->decimal('devoluciones', 12, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->json('desglose_pagos')->nullable();
            $table->json('productos')->nullable();
            $table->timestamps();
        });
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
        $data['metodo_pago'] = $this->normalizeMetodoPago($data['metodo_pago'] ?? null);

        if (! $data['metodo_pago']) {
            abort(422, 'Seleccione un método de pago válido.');
        }

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

    private function normalizeMetodoPago(?string $metodo): ?string
    {
        if (! is_string($metodo)) {
            return null;
        }

        $metodoNormalizado = strtolower(trim($metodo));

        return $metodoNormalizado !== '' ? $metodoNormalizado : null;
    }

    private function authorizeCierreAcceso(CierreCaja $cierreCaja): void
    {
        $esAdmin = (Auth::user()->role ?? null) === 'admin';

        if ($esAdmin) {
            return;
        }

        if ($cierreCaja->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
