<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
