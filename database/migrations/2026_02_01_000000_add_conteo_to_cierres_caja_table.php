<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cierres_caja', function (Blueprint $table) {
            if (! Schema::hasColumn('cierres_caja', 'conteo')) {
                $table->json('conteo')->nullable()->after('productos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cierres_caja', function (Blueprint $table) {
            if (Schema::hasColumn('cierres_caja', 'conteo')) {
                $table->dropColumn('conteo');
            }
        });
    }
};
