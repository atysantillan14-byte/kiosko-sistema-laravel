<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasTableSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Bebidas',
                'color' => '#3B82F6',
                'descripcion' => 'Refrescos, aguas, jugos',
                'activo' => true
            ],
            [
                'nombre' => 'Snacks',
                'color' => '#10B981',
                'descripcion' => 'Papas, galletas, chocolates',
                'activo' => true
            ],
            [
                'nombre' => 'Lácteos',
                'color' => '#8B5CF6',
                'descripcion' => 'Leche, yogur, queso',
                'activo' => true
            ],
            [
                'nombre' => 'Panadería',
                'color' => '#F59E0B',
                'descripcion' => 'Pan, facturas, tortas',
                'activo' => true
            ],
            [
                'nombre' => 'Limpieza',
                'color' => '#EF4444',
                'descripcion' => 'Productos de limpieza del hogar',
                'activo' => true
            ]
        ];

        DB::table('categorias')->insert($categorias);
    }
}