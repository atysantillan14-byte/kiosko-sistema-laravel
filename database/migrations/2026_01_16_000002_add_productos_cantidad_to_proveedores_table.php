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
            if (! Schema::hasColumn('proveedores', 'productos')) {
                $table->text('productos')->nullable()->after('condiciones_pago');
            }

            if (! Schema::hasColumn('proveedores', 'cantidad')) {
                $table->unsignedInteger('cantidad')->nullable()->after('productos');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('proveedores')) {
            return;
        }

        Schema::table('proveedores', function (Blueprint $table) {
            if (Schema::hasColumn('proveedores', 'cantidad')) {
                $table->dropColumn('cantidad');
            }

            if (Schema::hasColumn('proveedores', 'productos')) {
                $table->dropColumn('productos');
            }
        });
    }
};
