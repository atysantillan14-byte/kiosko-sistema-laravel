<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProveedorController extends Controller
{
    public function index(): View
    {
        if (! $this->ensureProveedoresTable()) {
            $proveedores = collect();

            return view('proveedores.index', compact('proveedores'))
                ->with('error', 'La tabla de proveedores no está disponible. Ejecutá las migraciones para crearla.');
        }

        $proveedores = Proveedor::query()->orderBy('nombre')->get();

        return view('proveedores.index', compact('proveedores'));
    }

    public function create(): View
    {
        return view('proveedores.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'contacto' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'condiciones_pago' => ['nullable', 'string', 'max:255'],
            'productos' => ['nullable', 'string', 'max:1000'],
            'cantidad' => ['nullable', 'integer', 'min:0'],
            'notas' => ['nullable', 'string'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $data['activo'] = $request->boolean('activo');

        if (! $this->ensureProveedoresTable()) {
            return back()
                ->withInput()
                ->with('error', 'La tabla de proveedores no está disponible. Ejecutá las migraciones para crearla.');
        }

        Proveedor::create($data);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado.');
    }

    public function edit(Proveedor $proveedor): View
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'contacto' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'condiciones_pago' => ['nullable', 'string', 'max:255'],
            'productos' => ['nullable', 'string', 'max:1000'],
            'cantidad' => ['nullable', 'integer', 'min:0'],
            'notas' => ['nullable', 'string'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $data['activo'] = $request->boolean('activo');

        $proveedor->update($data);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado.');
    }

    public function destroy(Proveedor $proveedor): RedirectResponse
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado.');
    }

    private function ensureProveedoresTable(): bool
    {
        if (Schema::hasTable('proveedores')) {
            return true;
        }

        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('contacto')->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('direccion')->nullable();
            $table->string('condiciones_pago')->nullable();
            $table->text('productos')->nullable();
            $table->unsignedInteger('cantidad')->nullable();
            $table->text('notas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        return Schema::hasTable('proveedores');
    }
}
