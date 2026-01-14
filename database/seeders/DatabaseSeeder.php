<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Admin fijo (siempre)
        User::updateOrCreate(
            ['email' => 'adrian@gmail.com'],
            [
                'name' => 'adrian',
                'password' => Hash::make('admin1234'),
                'role' => 'admin', // si tenés role en users
            ]
        );

        // 🔽 Luego el resto (en orden)
        $this->call([
        CategoriaSeeder::class,
        ProductoSeeder::class,
        DemoDataSeeder::class,
        ]);
    }
}

