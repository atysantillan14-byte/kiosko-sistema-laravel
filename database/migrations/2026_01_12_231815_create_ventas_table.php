<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('total', 12, 2)->default(0);
            $table->string('metodo_pago')->default('efectivo'); // efectivo, debito, credito, transferencia
            $table->string('metodo_pago_primario')->nullable();
            $table->string('metodo_pago_secundario')->nullable();
            $table->decimal('monto_primario', 12, 2)->nullable();
            $table->decimal('monto_secundario', 12, 2)->nullable();
            $table->decimal('efectivo_recibido', 12, 2)->nullable();
            $table->decimal('efectivo_cambio', 12, 2)->nullable();
            $table->string('estado')->default('completada'); // completada, anulada
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');

    }
};
