<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('proveedores')) {
            return;
        }

        Schema::table('proveedores', function (Blueprint $table) {
            if (! Schema::hasColumn('proveedores', 'productos_detalle')) {
                $table->json('productos_detalle')->nullable()->after('productos');
            }

            if (! Schema::hasColumn('proveedores', 'hora')) {
                $table->string('hora', 5)->nullable()->after('cantidad');
            }

            if (! Schema::hasColumn('proveedores', 'pago')) {
                $table->decimal('pago', 10, 2)->nullable()->after('hora');
            }

            if (! Schema::hasColumn('proveedores', 'deuda')) {
                $table->decimal('deuda', 10, 2)->nullable()->after('pago');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('proveedores')) {
            return;
        }

        Schema::table('proveedores', function (Blueprint $table) {
            if (Schema::hasColumn('proveedores', 'deuda')) {
                $table->dropColumn('deuda');
            }

            if (Schema::hasColumn('proveedores', 'pago')) {
                $table->dropColumn('pago');
            }

            if (Schema::hasColumn('proveedores', 'hora')) {
                $table->dropColumn('hora');
            }

            if (Schema::hasColumn('proveedores', 'productos_detalle')) {
                $table->dropColumn('productos_detalle');
            }
        });
    }
};
