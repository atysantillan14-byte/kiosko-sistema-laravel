<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();

            $table->decimal('precio', 10, 2);
            $table->decimal('precio_descuento', 10, 2)->nullable();

            $table->integer('stock')->default(0);

            $table->string('sku')->nullable();
            $table->string('imagen')->nullable();
            $table->json('imagenes_adicionales')->nullable();

            $table->boolean('disponible')->default(true);
            $table->boolean('destacado')->default(false);

            // IMPORTANTE: tiene que apuntar a 'categorias'
            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
