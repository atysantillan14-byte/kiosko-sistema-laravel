<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $this->rebuildSqliteTables();
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE productos ALTER COLUMN stock TYPE numeric(10,2)');
            DB::statement('ALTER TABLE productos ALTER COLUMN stock SET DEFAULT 0');
            DB::statement('ALTER TABLE productos ALTER COLUMN stock SET NOT NULL');

            DB::statement('ALTER TABLE venta_items ALTER COLUMN cantidad TYPE numeric(10,2)');
            DB::statement('ALTER TABLE venta_items ALTER COLUMN cantidad SET NOT NULL');

            DB::statement('ALTER TABLE detalle_ventas ALTER COLUMN cantidad TYPE numeric(10,2)');
            DB::statement('ALTER TABLE detalle_ventas ALTER COLUMN cantidad SET NOT NULL');

            return;
        }

        DB::statement('ALTER TABLE productos MODIFY stock DECIMAL(10,2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE venta_items MODIFY cantidad DECIMAL(10,2) NOT NULL');
        DB::statement('ALTER TABLE detalle_ventas MODIFY cantidad DECIMAL(10,2) NOT NULL');
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $this->rebuildSqliteTables(true);
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE productos ALTER COLUMN stock TYPE integer');
            DB::statement('ALTER TABLE productos ALTER COLUMN stock SET DEFAULT 0');
            DB::statement('ALTER TABLE productos ALTER COLUMN stock SET NOT NULL');

            DB::statement('ALTER TABLE venta_items ALTER COLUMN cantidad TYPE integer');
            DB::statement('ALTER TABLE venta_items ALTER COLUMN cantidad SET NOT NULL');

            DB::statement('ALTER TABLE detalle_ventas ALTER COLUMN cantidad TYPE integer');
            DB::statement('ALTER TABLE detalle_ventas ALTER COLUMN cantidad SET NOT NULL');

            return;
        }

        DB::statement('ALTER TABLE productos MODIFY stock INTEGER NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE venta_items MODIFY cantidad INTEGER NOT NULL');
        DB::statement('ALTER TABLE detalle_ventas MODIFY cantidad INTEGER NOT NULL');
    }

    private function rebuildSqliteTables(bool $revert = false): void
    {
        Schema::disableForeignKeyConstraints();

        $this->rebuildSqliteProductos($revert);
        $this->rebuildSqliteVentaItems($revert);
        $this->rebuildSqliteDetalleVentas($revert);

        Schema::enableForeignKeyConstraints();
    }

    private function rebuildSqliteProductos(bool $revert): void
    {
        Schema::rename('productos', 'productos_old');

        Schema::create('productos', function (Blueprint $table) use ($revert) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->decimal('precio_descuento', 10, 2)->nullable();
            if ($revert) {
                $table->integer('stock')->default(0);
            } else {
                $table->decimal('stock', 10, 2)->default(0);
            }
            $table->string('sku')->nullable();
            $table->string('imagen')->nullable();
            $table->json('imagenes_adicionales')->nullable();
            $table->boolean('disponible')->default(true);
            $table->boolean('destacado')->default(false);
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->timestamps();
        });

        DB::statement('INSERT INTO productos (id, nombre, slug, descripcion, precio, precio_descuento, stock, sku, imagen, imagenes_adicionales, disponible, destacado, categoria_id, created_at, updated_at)
            SELECT id, nombre, slug, descripcion, precio, precio_descuento, stock, sku, imagen, imagenes_adicionales, disponible, destacado, categoria_id, created_at, updated_at
            FROM productos_old');

        Schema::drop('productos_old');
    }

    private function rebuildSqliteVentaItems(bool $revert): void
    {
        Schema::rename('venta_items', 'venta_items_old');

        Schema::create('venta_items', function (Blueprint $table) use ($revert) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos');
            if ($revert) {
                $table->integer('cantidad');
            } else {
                $table->decimal('cantidad', 10, 2);
            }
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        DB::statement('INSERT INTO venta_items (id, venta_id, producto_id, cantidad, precio_unitario, subtotal, created_at, updated_at)
            SELECT id, venta_id, producto_id, cantidad, precio_unitario, subtotal, created_at, updated_at
            FROM venta_items_old');

        Schema::drop('venta_items_old');
    }

    private function rebuildSqliteDetalleVentas(bool $revert): void
    {
        Schema::rename('detalle_ventas', 'detalle_ventas_old');

        Schema::create('detalle_ventas', function (Blueprint $table) use ($revert) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            if ($revert) {
                $table->integer('cantidad');
            } else {
                $table->decimal('cantidad', 10, 2);
            }
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        DB::statement('INSERT INTO detalle_ventas (id, venta_id, producto_id, cantidad, precio_unitario, subtotal, created_at, updated_at)
            SELECT id, venta_id, producto_id, cantidad, precio_unitario, subtotal, created_at, updated_at
            FROM detalle_ventas_old');

        Schema::drop('detalle_ventas_old');
    }
};
