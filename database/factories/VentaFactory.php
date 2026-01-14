<?php

namespace Database\Factories;

use App\Models\Venta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VentaFactory extends Factory
{
    protected $model = Venta::class;

    public function definition(): array
    {
        $metodos = ['Efectivo', 'Transferencia', 'Tarjeta', 'MercadoPago'];

        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'total' => 0, // se calcula en el Seeder
            'metodo_pago' => $this->faker->randomElement($metodos),
            'estado' => 'completada',
        ];
    }
}
