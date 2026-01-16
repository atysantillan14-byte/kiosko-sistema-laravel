<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CierreCaja extends Model
{
    use HasFactory;

    protected $table = 'cierres_caja';

    protected $fillable = [
        'user_id',
        'desde',
        'hasta',
        'hora_desde',
        'hora_hasta',
        'turno',
        'total_bruto',
        'total_neto',
        'total_descuentos',
        'cantidad_ventas',
        'ticket_promedio',
        'efectivo_ventas',
        'efectivo_esperado',
        'efectivo_contado',
        'diferencia',
        'fondo_inicial',
        'ingresos',
        'retiros',
        'devoluciones',
        'observaciones',
        'desglose_pagos',
        'productos',
    ];

    protected $casts = [
        'desde' => 'date',
        'hasta' => 'date',
        'total_bruto' => 'decimal:2',
        'total_neto' => 'decimal:2',
        'total_descuentos' => 'decimal:2',
        'ticket_promedio' => 'decimal:2',
        'efectivo_ventas' => 'decimal:2',
        'efectivo_esperado' => 'decimal:2',
        'efectivo_contado' => 'decimal:2',
        'diferencia' => 'decimal:2',
        'fondo_inicial' => 'decimal:2',
        'ingresos' => 'decimal:2',
        'retiros' => 'decimal:2',
        'devoluciones' => 'decimal:2',
        'desglose_pagos' => 'array',
        'productos' => 'array',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
