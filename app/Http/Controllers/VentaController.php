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

        $ventasQuery = Venta::query()->with('usuario');

        if ($desde) {
            $ventasQuery->whereDate('created_at', '>=', $desde);
        }
        if ($hasta) {
            $ventasQuery->whereDate('created_at', '<=', $hasta);
        }

        if ($buscar !== '') {
            $ventasQuery->where(function ($q) use ($buscar) {
                $q->where('id', $buscar)
                  ->orWhere('metodo_pago', 'like', "%{$buscar}%")
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
    $usuarios = \App\Models\User::orderBy('name')->get(['id','name']);

    $productos = \App\Models\Producto::where('disponible', 1)
        ->orderBy('nombre')
        ->get(['id','nombre','precio','stock']);

    $productosForJs = $productos->map(function ($p) {
        return [
            'id'     => $p->id,
            'nombre' => $p->nombre,
            'precio' => (float) $p->precio,
            'stock'  => (int) $p->stock,
        ];
    })->values();

    return view('ventas.create', compact('usuarios', 'productos', 'productosForJs'));
}


    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'metodo_pago' => ['required', 'string', 'max:50'],
            'estado' => ['required', 'string', 'max:40'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'exists:productos,id'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($data) {
            $venta = Venta::create([
                'user_id' => $data['user_id'],
                'metodo_pago' => $data['metodo_pago'],
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

            $venta->update(['total' => $total]);

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


