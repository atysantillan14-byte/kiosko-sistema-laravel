<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VentaItem extends Model
{
    use HasFactory;

    protected $table = 'venta_items';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
