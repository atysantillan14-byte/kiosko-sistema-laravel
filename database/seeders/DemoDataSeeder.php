<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // 1) Usuarios demo (si no existen)
            $usuarios = User::query()->get();
            if ($usuarios->count() < 3) {
                // crea algunos cajeros demo
                for ($i=1; $i<=5; $i++) {
                    User::firstOrCreate(
                        ['email' => "cajero{$i}@demo.com"],
                        [
                            'name' => "Cajero {$i}",
                            'password' => bcrypt('12345678'),
                        ]
                    );
                }
            }
            $usuarios = User::query()->get();

            // 2) Categorías demo (si no existen suficientes)
            $catNames = [
                'Bebidas', 'Golosinas', 'Lácteos', 'Panadería', 'Snacks',
                'Almacén', 'Limpieza', 'Cigarrillos', 'Helados', 'Congelados'
            ];

            foreach ($catNames as $name) {
                Categoria::firstOrCreate(
                    ['nombre' => $name],
                    ['slug' => Str::slug($name)]
                );
            }
            $categorias = Categoria::all();

            // 3) Productos demo (si hay pocos, creamos muchos)
            if (Producto::count() < 120) {
                $baseProductos = [
                    'Coca Cola 500ml', 'Pepsi 500ml', 'Agua 500ml', 'Juguito 200ml', 'Energizante 473ml',
                    'Alfajor Chocolate', 'Alfajor Blanco', 'Galletitas Dulces', 'Galletitas Saladas', 'Chocolate Barra',
                    'Leche Entera 1L', 'Leche Descremada 1L', 'Yogur', 'Queso Cremoso', 'Manteca',
                    'Pan Lactal', 'Facturas', 'Bizcochos', 'Budín', 'Tostadas',
                    'Papas Fritas', 'Maní', 'Palitos', 'Chizitos', 'Nachos',
                    'Arroz 1Kg', 'Fideos', 'Azúcar 1Kg', 'Yerba 1Kg', 'Aceite 900ml',
                    'Detergente', 'Lavandina', 'Jabón', 'Shampoo', 'Papel Higiénico'
                ];

                // Generamos ~150 productos combinando base + variaciones
                $toCreate = 150;
                for ($i=1; $i<=$toCreate; $i++) {
                    $nombre = $baseProductos[array_rand($baseProductos)];
                    $nombre = $nombre.' '.Str::upper(Str::random(3))." #$i";

                    $precio = random_int(200, 5000);
                    $stock  = random_int(0, 120);

                    Producto::create([
                        'nombre' => $nombre,
                        'slug' => Str::slug($nombre).'-'.Str::random(5),
                        'descripcion' => 'Producto demo generado automáticamente.',
                        'precio' => $precio,
                        'precio_descuento' => null,
                        'stock' => $stock,
                        'sku' => 'SKU-'.Str::upper(Str::random(8)),
                        'imagen' => null,
                        'imagenes_adicionales' => null,
                        'disponible' => 1,
                        'destacado' => (random_int(1, 10) <= 2) ? 1 : 0,
                        'categoria_id' => $categorias->random()->id,
                    ]);
                }
            }

            // 4) Ventas + Detalles
            // si ya hay muchas ventas, no duplicamos tanto
            if (Venta::count() < 400) {
                $productos = Producto::query()->where('disponible', 1)->get();
                if ($productos->isEmpty()) return;

                $metodos = ['Efectivo', 'Transferencia', 'Débito', 'Crédito', 'QR'];
                $estados = ['confirmada', 'confirmada', 'confirmada', 'anulada']; // mayoría confirmadas

                // Generamos 900 ventas en los últimos 90 días
                $ventasACrear = 900;

                for ($i=1; $i<=$ventasACrear; $i++) {
                    $usuario = $usuarios->random();
                    $metodo  = $metodos[array_rand($metodos)];
                    $estado  = $estados[array_rand($estados)];

                    $fecha = Carbon::now()
                        ->subDays(random_int(0, 90))
                        ->setTime(random_int(8, 23), random_int(0, 59), random_int(0, 59));

                    $venta = Venta::create([
                        'user_id' => $usuario->id,
                        'total' => 0,
                        'metodo_pago' => $metodo,
                        'estado' => $estado,
                        'created_at' => $fecha,
                        'updated_at' => $fecha,
                    ]);

                    // detalles (1 a 6 items)
                    $items = random_int(1, 6);
                    $totalVenta = 0;

                    for ($d=1; $d<=$items; $d++) {
                        $producto = $productos->random();

                        $cantidad = random_int(1, 4);
                        $precio = (float) $producto->precio; // sin descuento por ahora
                        $subtotal = $cantidad * $precio;

                        DetalleVenta::create([
                            'venta_id' => $venta->id,
                            'producto_id' => $producto->id,
                            'cantidad' => $cantidad,
                            'precio_unitario' => $precio,
                            'subtotal' => $subtotal,
                            'created_at' => $fecha,
                            'updated_at' => $fecha,
                        ]);

                        $totalVenta += $subtotal;

                        // descontar stock si está confirmada
                        if ($estado !== 'anulada') {
                            $producto->stock = max(0, $producto->stock - $cantidad);
                            $producto->save();
                        }
                    }

                    // guardar total
                    $venta->total = $totalVenta;
                    $venta->save();
                }
            }
        });
    }
}
