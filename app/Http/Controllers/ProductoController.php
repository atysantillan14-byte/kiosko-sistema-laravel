<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')
            ->orderBy('id', 'desc')
            ->get();

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:productos,slug'],
            'sku' => ['nullable', 'string', 'max:80', 'unique:productos,sku'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'precio_descuento' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'imagen' => ['nullable', 'string', 'max:255'],
            'imagenes_adicionales' => ['nullable'], // array en el modelo
            'disponible' => ['nullable', 'boolean'],
            'destacado' => ['nullable', 'boolean'],
        ]);

        $data['disponible'] = $request->boolean('disponible');
        $data['destacado'] = $request->boolean('destacado');
        $data['stock'] = $data['stock'] ?? 0;
        $data['precio_descuento'] = $data['precio_descuento'] ?? null;

        Producto::create($data);

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:productos,slug,' . $producto->id],
            'sku' => ['nullable', 'string', 'max:80', 'unique:productos,sku,' . $producto->id],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'precio_descuento' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'imagen' => ['nullable', 'string', 'max:255'],
            'imagenes_adicionales' => ['nullable'],
            'disponible' => ['nullable', 'boolean'],
            'destacado' => ['nullable', 'boolean'],
        ]);

        $data['disponible'] = $request->boolean('disponible');
        $data['destacado'] = $request->boolean('destacado');
        $data['stock'] = $data['stock'] ?? 0;

        $producto->update($data);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }

    public function show(Producto $producto)
    {
        return redirect()->route('productos.index');
    }
}
