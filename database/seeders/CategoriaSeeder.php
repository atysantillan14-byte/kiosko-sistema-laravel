<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Golosinas',
            'Bebidas',
            'Cigarrillos',
            'Almacén',
            'Lácteos',
        ];

        foreach ($categorias as $i => $nombre) {
            Categoria::updateOrCreate(
                ['slug' => Str::slug($nombre)],
                [
                    'nombre' => $nombre,
                    'descripcion' => 'Categoría ' . $nombre,
                    'activo' => true,
                    'orden' => $i,
                ]
            );
        }
    }
}
