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
            'productos_detalle' => ['nullable', 'array'],
            'productos_detalle.*.nombre' => ['nullable', 'string', 'max:255'],
            'productos_detalle.*.cantidad' => ['nullable', 'integer', 'min:0'],
            'cantidad' => ['nullable', 'integer', 'min:0'],
            'hora' => ['nullable', 'date_format:H:i'],
            'pago' => ['nullable', 'numeric', 'min:0'],
            'deuda' => ['nullable', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $data['activo'] = $request->boolean('activo');
        $data['productos_detalle'] = $this->sanitizeProductosDetalle($request->input('productos_detalle', []));

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
            'productos_detalle' => ['nullable', 'array'],
            'productos_detalle.*.nombre' => ['nullable', 'string', 'max:255'],
            'productos_detalle.*.cantidad' => ['nullable', 'integer', 'min:0'],
            'cantidad' => ['nullable', 'integer', 'min:0'],
            'hora' => ['nullable', 'date_format:H:i'],
            'pago' => ['nullable', 'numeric', 'min:0'],
            'deuda' => ['nullable', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $data['activo'] = $request->boolean('activo');
        $data['productos_detalle'] = $this->sanitizeProductosDetalle($request->input('productos_detalle', []));

        if (! $this->ensureProveedoresTable()) {
            return back()
                ->withInput()
                ->with('error', 'La tabla de proveedores no está disponible. Ejecutá las migraciones para crearla.');
        }

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
            $this->ensureProveedoresColumns();

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
            $table->json('productos_detalle')->nullable();
            $table->unsignedInteger('cantidad')->nullable();
            $table->string('hora', 5)->nullable();
            $table->decimal('pago', 10, 2)->nullable();
            $table->decimal('deuda', 10, 2)->nullable();
            $table->text('notas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        return Schema::hasTable('proveedores');
    }

    private function ensureProveedoresColumns(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            if (! Schema::hasColumn('proveedores', 'contacto')) {
                $table->string('contacto')->nullable()->after('nombre');
            }

            if (! Schema::hasColumn('proveedores', 'telefono')) {
                $table->string('telefono', 50)->nullable()->after('contacto');
            }

            if (! Schema::hasColumn('proveedores', 'email')) {
                $table->string('email')->nullable()->after('telefono');
            }

            if (! Schema::hasColumn('proveedores', 'direccion')) {
                $table->string('direccion')->nullable()->after('email');
            }

            if (! Schema::hasColumn('proveedores', 'condiciones_pago')) {
                $table->string('condiciones_pago')->nullable()->after('direccion');
            }

            if (! Schema::hasColumn('proveedores', 'productos')) {
                $table->text('productos')->nullable()->after('condiciones_pago');
            }

            if (! Schema::hasColumn('proveedores', 'productos_detalle')) {
                $table->json('productos_detalle')->nullable()->after('productos');
            }

            if (! Schema::hasColumn('proveedores', 'cantidad')) {
                $table->unsignedInteger('cantidad')->nullable()->after('productos_detalle');
            }

            if (! Schema::hasColumn('proveedores', 'hora')) {
                $table->string('hora', 5)->nullable()->after('cantidad');
            }

            if (! Schema::hasColumn('proveedores', 'pago')) {
                $table->decimal('pago', 10, 2)->nullable()->after('hora');
            }

            if (! Schema::hasColumn('proveedores', 'deuda')) {
                $table->decimal('deuda', 10, 2)->nullable()->after('pago');
            }

            if (! Schema::hasColumn('proveedores', 'notas')) {
                $table->text('notas')->nullable()->after('deuda');
            }

            if (! Schema::hasColumn('proveedores', 'activo')) {
                $table->boolean('activo')->default(true)->after('notas');
            }
        });
    }

    private function sanitizeProductosDetalle(array $productosDetalle): ?array
    {
        $items = collect($productosDetalle)
            ->map(function ($item) {
                return [
                    'nombre' => isset($item['nombre']) ? trim((string) $item['nombre']) : null,
                    'cantidad' => isset($item['cantidad']) && $item['cantidad'] !== '' ? (int) $item['cantidad'] : null,
                ];
            })
            ->filter(function ($item) {
                return filled($item['nombre']) || $item['cantidad'] !== null;
            })
            ->values()
            ->all();

        return $items !== [] ? $items : null;
    }
}
