<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
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
            'acciones' => ['nullable', 'array'],
            'acciones.*.fecha' => ['nullable', 'date'],
            'acciones.*.hora' => ['nullable', 'date_format:H:i'],
            'acciones.*.tipo' => ['nullable', 'string', 'max:100'],
            'acciones.*.productos' => ['nullable', 'string', 'max:1000'],
            'acciones.*.cantidad' => ['nullable', 'integer', 'min:0'],
            'acciones.*.monto' => ['nullable', 'numeric', 'min:0'],
            'acciones.*.monto_productos' => ['nullable', 'numeric', 'min:0'],
            'acciones.*.deuda_pendiente' => ['nullable', 'numeric', 'min:0'],
            'acciones.*.notas' => ['nullable', 'string', 'max:1000'],
            'fecha_visita' => ['required', 'date'],
            'cantidad' => ['nullable', 'integer', 'min:0'],
            'hora' => ['nullable', 'date_format:H:i'],
            'proxima_visita' => ['nullable', 'date'],
            'pago' => ['nullable', 'numeric', 'min:0'],
            'deuda' => ['nullable', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $this->ensureAccionesConMonto($request->input('acciones', []));

        $fechaVisita = $data['fecha_visita'] ?? null;
        unset($data['fecha_visita']);

        $data['activo'] = $request->boolean('activo');
        $data['productos_detalle'] = $this->sanitizeProductosDetalle($request->input('productos_detalle', []));
        $acciones = $this->sanitizeAcciones($request->input('acciones', [])) ?? [];

        if ($fechaVisita) {
            $acciones[] = [
                'fecha' => Carbon::parse($fechaVisita)->toDateString(),
                'hora' => null,
                'tipo' => 'Visita inicial',
                'productos' => null,
                'cantidad' => null,
                'monto' => 0,
                'notas' => 'Alta de proveedor.',
            ];
        }

        $data['acciones'] = $this->sanitizeAcciones($acciones);

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

    public function show(Proveedor $proveedor): View
    {
        return view('proveedores.show', compact('proveedor'));
    }

    public function storeAccion(Request $request, Proveedor $proveedor): RedirectResponse
    {
        $data = $request->validate([
            'tipo' => ['nullable', 'string', 'max:100'],
            'fecha' => ['nullable', 'date'],
            'hora' => ['nullable', 'date_format:H:i'],
            'proxima_visita' => ['nullable', 'date'],
            'productos' => ['nullable', 'string', 'max:1000'],
            'productos_detalle' => ['nullable', 'array'],
            'productos_detalle.*.nombre' => ['nullable', 'string', 'max:255'],
            'productos_detalle.*.cantidad' => ['nullable', 'integer', 'min:0'],
            'cantidad' => ['nullable', 'integer', 'min:0'],
            'monto' => ['nullable', 'numeric', 'min:0'],
            'monto_productos' => ['nullable', 'numeric', 'min:0'],
            'deuda_pendiente' => ['nullable', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string', 'max:1000'],
        ]);

        $productosDetalle = $this->sanitizeProductosDetalle($request->input('productos_detalle', []));
        $productosDetalleResumen = null;
        $cantidadDetalle = null;

        if ($productosDetalle) {
            $productosDetalleResumen = collect($productosDetalle)
                ->map(function ($item) {
                    $nombre = $item['nombre'] ?? null;
                    $cantidad = $item['cantidad'] ?? null;

                    if ($nombre && $cantidad !== null) {
                        return sprintf('%s (%s)', $nombre, $cantidad);
                    }

                    return $nombre ?: null;
                })
                ->filter()
                ->implode(', ');

            $cantidadDetalle = collect($productosDetalle)
                ->map(fn ($item) => (int) ($item['cantidad'] ?? 0))
                ->sum();
        }

        $fecha = filled($data['fecha'] ?? null)
            ? Carbon::parse($data['fecha'])->toDateString()
            : Carbon::now()->toDateString();
        $hora = filled($data['hora'] ?? null)
            ? $data['hora']
            : Carbon::now()->format('H:i');

        $tipoAccion = strtolower((string) ($data['tipo'] ?? ''));
        $monto = $data['monto'] ?? null;
        $montoProductos = $data['monto_productos'] ?? null;
        $esPagoDeuda = str_starts_with($tipoAccion, 'pago') && str_contains($tipoAccion, 'deuda');

        if (str_starts_with($tipoAccion, 'pago') && $monto === null && $montoProductos !== null) {
            $monto = $montoProductos;
            $montoProductos = null;
        }

        $deudaPendienteAccion = $esPagoDeuda ? null : ($data['deuda_pendiente'] ?? null);
        $productosTexto = $esPagoDeuda
            ? null
            : ($productosDetalleResumen
                ?? (isset($data['productos']) ? trim((string) $data['productos']) : null));
        $cantidadAccion = $esPagoDeuda
            ? null
            : ($productosDetalle ? ($cantidadDetalle ?: null) : ($data['cantidad'] ?? null));
        if ($esPagoDeuda) {
            $montoProductos = null;
        }

        $accion = [
            'fecha' => $fecha,
            'hora' => $hora,
            'tipo' => filled($data['tipo'] ?? null) ? $data['tipo'] : 'Acción',
            'productos' => $productosTexto,
            'cantidad' => $cantidadAccion,
            'monto' => $monto,
            'monto_productos' => $montoProductos,
            'deuda_pendiente' => $deudaPendienteAccion,
            'notas' => isset($data['notas']) ? trim((string) $data['notas']) : null,
        ];

        $accionesActuales = $proveedor->acciones ?? [];
        $accionesActuales[] = $accion;
        $updates = [
            'acciones' => $this->sanitizeAcciones($accionesActuales),
        ];
        if (filled($data['proxima_visita'] ?? null)) {
            $updates['proxima_visita'] = $data['proxima_visita'];
        }

        $proveedor->update($updates);

        return redirect()
            ->route('proveedores.show', $proveedor)
            ->with('success', 'Acción registrada para el proveedor.');
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
            'acciones' => ['nullable', 'array'],
            'acciones.*.fecha' => ['nullable', 'date'],
            'acciones.*.hora' => ['nullable', 'date_format:H:i'],
            'acciones.*.tipo' => ['nullable', 'string', 'max:100'],
            'acciones.*.productos' => ['nullable', 'string', 'max:1000'],
            'acciones.*.cantidad' => ['nullable', 'integer', 'min:0'],
            'acciones.*.monto' => ['nullable', 'numeric', 'min:0'],
            'acciones.*.monto_productos' => ['nullable', 'numeric', 'min:0'],
            'acciones.*.deuda_pendiente' => ['nullable', 'numeric', 'min:0'],
            'acciones.*.notas' => ['nullable', 'string', 'max:1000'],
            'cantidad' => ['nullable', 'integer', 'min:0'],
            'hora' => ['nullable', 'date_format:H:i'],
            'proxima_visita' => ['nullable', 'date'],
            'pago' => ['nullable', 'numeric', 'min:0'],
            'deuda' => ['nullable', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $this->ensureAccionesConMonto($request->input('acciones', []));

        $data['activo'] = $request->boolean('activo');
        $data['productos_detalle'] = $this->sanitizeProductosDetalle($request->input('productos_detalle', []));
        $data['acciones'] = $this->sanitizeAcciones($request->input('acciones', []));

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
            $table->json('acciones')->nullable();
            $table->unsignedInteger('cantidad')->nullable();
            $table->string('hora', 5)->nullable();
            $table->date('proxima_visita')->nullable();
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

            if (! Schema::hasColumn('proveedores', 'acciones')) {
                $table->json('acciones')->nullable()->after('productos_detalle');
            }

            if (! Schema::hasColumn('proveedores', 'cantidad')) {
                $table->unsignedInteger('cantidad')->nullable()->after('acciones');
            }

            if (! Schema::hasColumn('proveedores', 'hora')) {
                $table->string('hora', 5)->nullable()->after('cantidad');
            }

            if (! Schema::hasColumn('proveedores', 'proxima_visita')) {
                $table->date('proxima_visita')->nullable()->after('hora');
            }

            if (! Schema::hasColumn('proveedores', 'pago')) {
                $table->decimal('pago', 10, 2)->nullable()->after('proxima_visita');
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

    private function sanitizeAcciones(array $acciones): ?array
    {
        $items = collect($acciones)
            ->map(function ($item) {
                $fecha = isset($item['fecha']) && filled($item['fecha'])
                    ? Carbon::parse($item['fecha'])->toDateString()
                    : null;

                $hora = isset($item['hora']) && filled($item['hora'])
                    ? $item['hora']
                    : null;

                return [
                    'fecha' => $fecha,
                    'hora' => $hora,
                    'tipo' => isset($item['tipo']) ? trim((string) $item['tipo']) : null,
                    'productos' => isset($item['productos']) ? trim((string) $item['productos']) : null,
                    'cantidad' => isset($item['cantidad']) && $item['cantidad'] !== '' ? (int) $item['cantidad'] : null,
                    'monto' => isset($item['monto']) && $item['monto'] !== '' ? (float) $item['monto'] : null,
                    'monto_productos' => isset($item['monto_productos']) && $item['monto_productos'] !== ''
                        ? (float) $item['monto_productos']
                        : null,
                    'deuda_pendiente' => isset($item['deuda_pendiente']) && $item['deuda_pendiente'] !== ''
                        ? (float) $item['deuda_pendiente']
                        : null,
                    'notas' => isset($item['notas']) ? trim((string) $item['notas']) : null,
                ];
            })
            ->filter(function ($item) {
                return filled($item['fecha'])
                    || filled($item['hora'])
                    || filled($item['tipo'])
                    || filled($item['productos'])
                    || $item['cantidad'] !== null
                    || $item['monto'] !== null
                    || $item['monto_productos'] !== null
                    || $item['deuda_pendiente'] !== null
                    || filled($item['notas']);
            })
            ->values()
            ->all();

        return $items !== [] ? $items : null;
    }

    private function ensureAccionesConMonto(array $acciones): void
    {
        return;
    }
}
