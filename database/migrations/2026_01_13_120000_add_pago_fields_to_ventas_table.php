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
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('metodo_pago_primario')->nullable()->after('metodo_pago');
            $table->string('metodo_pago_secundario')->nullable()->after('metodo_pago_primario');
            $table->decimal('monto_primario', 12, 2)->nullable()->after('metodo_pago_secundario');
            $table->decimal('monto_secundario', 12, 2)->nullable()->after('monto_primario');
            $table->decimal('efectivo_recibido', 12, 2)->nullable()->after('monto_secundario');
            $table->decimal('efectivo_cambio', 12, 2)->nullable()->after('efectivo_recibido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn([
                'metodo_pago_primario',
                'metodo_pago_secundario',
                'monto_primario',
                'monto_secundario',
                'efectivo_recibido',
                'efectivo_cambio',
            ]);
        });
    }
};
