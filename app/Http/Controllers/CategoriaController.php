<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::query()->orderBy('orden')->orderBy('nombre')->get();
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'estado' => ['nullable', 'string', 'max:40'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug'] = Str::slug($data['nombre']);
        $data['estado'] = $data['estado'] ?? 'activa';
        $data['orden'] = $data['orden'] ?? 0;

        Categoria::create($data);

        return redirect()->route('categorias.index')->with('success', 'Categoría creada.');
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'estado' => ['nullable', 'string', 'max:40'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug'] = Str::slug($data['nombre']);
        $data['estado'] = $data['estado'] ?? 'activa';
        $data['orden'] = $data['orden'] ?? 0;

        $categoria->update($data);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada.');
    }
}


