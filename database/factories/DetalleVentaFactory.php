<?php

namespace Database\Factories;

use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetalleVentaFactory extends Factory
{
    protected $model = DetalleVenta::class;

    public function definition(): array
    {
        $cantidad = $this->faker->numberBetween(1, 4);
        $precio = $this->faker->randomFloat(2, 100, 5000);

        return [
            'venta_id' => Venta::factory(),
            'producto_id' => Producto::query()->inRandomOrder()->value('id') ?? Producto::factory(),
            'cantidad' => $cantidad,
            'precio_unitario' => $precio,
            'subtotal' => $cantidad * $precio,
        ];
    }
}
