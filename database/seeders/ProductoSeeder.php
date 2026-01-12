<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $golosinas = Categoria::where('slug', 'golosinas')->first();
        $bebidas   = Categoria::where('slug', 'bebidas')->first();

        if (!$golosinas || !$bebidas) return;

        $items = [
            [
                'categoria_id' => $golosinas->id,
                'nombre' => 'Alfajor',
                'precio' => 800,
                'stock' => 20,
                'disponible' => true,
                'destacado' => false,
            ],
            [
                'categoria_id' => $golosinas->id,
                'nombre' => 'Caramelos',
                'precio' => 150,
                'stock' => 100,
                'disponible' => true,
                'destacado' => false,
            ],
            [
                'categoria_id' => $bebidas->id,
                'nombre' => 'Coca Cola 500ml',
                'precio' => 1600,
                'stock' => 15,
                'disponible' => true,
                'destacado' => true,
            ],
            [
                'categoria_id' => $bebidas->id,
                'nombre' => 'Agua 500ml',
                'precio' => 900,
                'stock' => 25,
                'disponible' => true,
                'destacado' => false,
            ],
        ];

        foreach ($items as $it) {
            Producto::updateOrCreate(
                ['slug' => Str::slug($it['nombre'])],
                array_merge($it, [
                    'slug' => Str::slug($it['nombre']),
                    'descripcion' => 'Producto de prueba',
                    'precio_descuento' => null,
                    'sku' => null,
                    'imagen' => null,
                    'imagenes_adicionales' => null,
                ])
            );
        }
    }
}

