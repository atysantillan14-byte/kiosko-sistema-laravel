<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            if (!Schema::hasColumn('ventas', 'metodo_pago_primario')) {
                $table->string('metodo_pago_primario')->nullable()->after('metodo_pago');
            }
            if (!Schema::hasColumn('ventas', 'metodo_pago_secundario')) {
                $table->string('metodo_pago_secundario')->nullable()->after('metodo_pago_primario');
            }
            if (!Schema::hasColumn('ventas', 'monto_primario')) {
                $table->decimal('monto_primario', 12, 2)->nullable()->after('metodo_pago_secundario');
            }
            if (!Schema::hasColumn('ventas', 'monto_secundario')) {
                $table->decimal('monto_secundario', 12, 2)->nullable()->after('monto_primario');
            }
            if (!Schema::hasColumn('ventas', 'efectivo_recibido')) {
                $table->decimal('efectivo_recibido', 12, 2)->nullable()->after('monto_secundario');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $columns = [
                'metodo_pago_primario',
                'metodo_pago_secundario',
                'monto_primario',
                'monto_secundario',
                'efectivo_recibido',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('ventas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
