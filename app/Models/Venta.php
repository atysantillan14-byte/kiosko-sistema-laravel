<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'user_id',
        'metodo_pago',
        'metodo_pago_primario',
        'metodo_pago_secundario',
        'monto_primario',
        'monto_secundario',
        'efectivo_recibido',
        'efectivo_cambio',
        'estado',
        'total',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'monto_primario' => 'decimal:2',
        'monto_secundario' => 'decimal:2',
        'efectivo_recibido' => 'decimal:2',
        'efectivo_cambio' => 'decimal:2',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Por si en algún lado usás ->user()
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(\App\Models\DetalleVenta::class, 'venta_id');
    }
}

