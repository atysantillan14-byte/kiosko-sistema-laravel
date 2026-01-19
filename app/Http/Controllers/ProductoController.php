<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $categoriaId = $request->query('categoria_id');

        $query = Producto::query()->with('categoria');

        if ($categoriaId) {
            $query->where('categoria_id', $categoriaId);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        $totalProductos = (clone $query)->count();

        $productos = $query
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $categorias = Categoria::query()->orderBy('nombre')->get();

        $topProductosVendidos = DB::table('detalle_ventas as dv')
            ->join('ventas as v', 'v.id', '=', 'dv.venta_id')
            ->leftJoin('productos as p', 'p.id', '=', 'dv.producto_id')
            ->where('v.estado', '!=', 'anulada')
            ->selectRaw('p.nombre as producto_nombre, SUM(dv.cantidad) as total_vendido')
            ->groupBy('p.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        $stockBajo = Producto::query()
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->limit(10)
            ->get(['id', 'nombre', 'stock']);

        return view('productos.index', compact(
            'productos',
            'categorias',
            'q',
            'categoriaId',
            'totalProductos',
            'topProductosVendidos',
            'stockBajo',
        ));
    }

    public function create()
    {
        $categorias = Categoria::query()->orderBy('nombre')->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'precio_descuento' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'numeric', 'min:0'],
            'sku' => ['nullable', 'string', 'max:80'],
            'imagen' => ['nullable', 'string', 'max:255'],
            'disponible' => ['nullable'],
            'destacado' => ['nullable'],
        ]);

        $data['slug'] = Str::slug($data['nombre']);
        $data['disponible'] = $request->boolean('disponible');
        $data['destacado'] = $request->boolean('destacado');

        if (empty($data['sku'])) {
            $data['sku'] = 'SKU-' . strtoupper(Str::random(8));
        }

        Producto::create($data);

        return redirect()->route('productos.index')->with('success', 'Producto creado.');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::query()->orderBy('nombre')->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'precio_descuento' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'numeric', 'min:0'],
            'sku' => ['nullable', 'string', 'max:80'],
            'imagen' => ['nullable', 'string', 'max:255'],
            'disponible' => ['nullable'],
            'destacado' => ['nullable'],
        ]);

        $data['slug'] = Str::slug($data['nombre']);
        $data['disponible'] = $request->boolean('disponible');
        $data['destacado'] = $request->boolean('destacado');

        $producto->update($data);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado.');
    }
}
    


