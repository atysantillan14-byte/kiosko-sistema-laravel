<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'contacto',
        'telefono',
        'email',
        'direccion',
        'condiciones_pago',
        'productos',
        'productos_detalle',
        'cantidad',
        'hora',
        'pago',
        'deuda',
        'notas',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'productos_detalle' => 'array',
    ];
}
