<?php

namespace Database\Seeders;

use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentaSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurar que haya al menos 1 usuario
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@kiosko.com',
                // password: admin1234
                'password' => bcrypt('admin1234'),
            ]);
        }

        // Tomamos productos con stock
        $productosIds = Producto::query()
            ->where('disponible', true)
            ->pluck('id')
            ->toArray();

        if (count($productosIds) === 0) {
            $this->command?->warn('No hay productos para vender. Ejecutá primero ProductoSeeder.');
            return;
        }

        $ventasACrear = 300; // 👈 subilo/bajalo a gusto (300–800 queda buenísimo)

        DB::transaction(function () use ($ventasACrear, $productosIds) {

            for ($i = 0; $i < $ventasACrear; $i++) {

                // Fecha aleatoria últimos 30 días (horario de kiosco)
                $createdAt = Carbon::now()
                    ->subDays(rand(0, 29))
                    ->setTime(rand(8, 22), rand(0, 59), rand(0, 59));

                $userId = User::query()->inRandomOrder()->value('id');

                $venta = Venta::create([
                    'user_id' => $userId,
                    'total' => 0,
                    'metodo_pago' => collect(['Efectivo', 'Transferencia', 'Tarjeta', 'MercadoPago'])->random(),
                    'estado' => 'completada',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $items = rand(1, 6);
                $totalVenta = 0;

                // Elegimos productos al azar sin repetir en la misma venta
                $seleccion = collect($productosIds)->shuffle()->take(min($items, count($productosIds)));

                foreach ($seleccion as $productoId) {
                    $producto = Producto::find($productoId);
                    if (!$producto) continue;

                    // Si no hay stock, saltamos
                    if ($producto->stock <= 0) continue;

                    $cantidad = rand(1, min(3, $producto->stock)); // vende 1 a 3 o lo que tenga
                    $precio = (float) $producto->precio;
                    $subtotal = $cantidad * $precio;

                    DetalleVenta::create([
                        'venta_id' => $venta->id,
                        'producto_id' => $producto->id,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precio,
                        'subtotal' => $subtotal,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);

                    // Restar stock
                    $producto->decrement('stock', $cantidad);

                    $totalVenta += $subtotal;
                }

                // Guardar total final (si por stock no vendió nada, eliminamos la venta vacía)
                if ($totalVenta <= 0) {
                    $venta->delete();
                } else {
                    $venta->update([
                        'total' => $totalVenta,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        });
    }
}



