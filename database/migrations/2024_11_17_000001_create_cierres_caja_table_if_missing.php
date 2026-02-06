<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cierres_caja')) {
            return;
        }

        Schema::create('cierres_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('desde')->nullable();
            $table->date('hasta')->nullable();
            $table->time('hora_desde')->nullable();
            $table->time('hora_hasta')->nullable();
            $table->string('turno', 20)->nullable();
            $table->decimal('total_bruto', 12, 2)->default(0);
            $table->decimal('total_neto', 12, 2)->default(0);
            $table->decimal('total_descuentos', 12, 2)->default(0);
            $table->unsignedInteger('cantidad_ventas')->default(0);
            $table->decimal('ticket_promedio', 12, 2)->default(0);
            $table->decimal('efectivo_ventas', 12, 2)->default(0);
            $table->decimal('efectivo_esperado', 12, 2)->default(0);
            $table->decimal('efectivo_contado', 12, 2)->default(0);
            $table->decimal('diferencia', 12, 2)->default(0);
            $table->decimal('fondo_inicial', 12, 2)->default(0);
            $table->decimal('ingresos', 12, 2)->default(0);
            $table->decimal('retiros', 12, 2)->default(0);
            $table->decimal('devoluciones', 12, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->json('desglose_pagos')->nullable();
            $table->json('productos')->nullable();
            $table->json('conteo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cierres_caja');
    }
};
