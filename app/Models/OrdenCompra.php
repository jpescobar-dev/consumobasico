<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'ordenescompras';

    protected $primaryKey = 'orden_compra';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'orden_compra',
        'nombre',
        'tipo',
        'estado',
        'unidad_compra',
        'proveedor',
        'rutproveedor',
        'fecha_creacion',
        'fecha_envio',
        'monto_neto',
        'descuentos',
        'cargos',
        'iva',
        'impuesto_especifico',
        'total',
    ];


     protected $casts = [
    'fecha_creacion' => 'datetime',
    'fecha_envio' => 'datetime',
    'deleted_at' => 'datetime',
    ];


   

    public function getRouteKeyName()
    {
        return 'orden_compra';
    }

    // Relación: una orden de compra pertenece a un proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'rutproveedor', 'rutproveedor');
    }
}
