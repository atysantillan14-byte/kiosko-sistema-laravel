<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'precio',
        'precio_descuento',
        'stock',
        'sku',
        'imagen',
        'imagenes_adicionales',
        'disponible',
        'destacado',
        'categoria_id',
    ];

    protected $casts = [
        'imagenes_adicionales' => 'array',
        'precio' => 'decimal:2',
        'precio_descuento' => 'decimal:2',
        'disponible' => 'boolean',
        'destacado' => 'boolean',
        'stock' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Producto $producto) {
            if (empty($producto->slug) && !empty($producto->nombre)) {
                $producto->slug = Str::slug($producto->nombre);
            }
        });
    }

    public function categoria()
{
    return $this->belongsTo(\App\Models\Categoria::class);
}


public function detallesVentas(): HasMany
{
    return $this->hasMany(DetalleVenta::class, 'producto_id');
}

}

