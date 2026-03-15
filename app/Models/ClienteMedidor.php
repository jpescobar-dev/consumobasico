<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteMedidor extends Model
{
    protected $table = 'clientesmedidores';
    protected $primaryKey = 'numerocliente';
    public $incrementing = false; // porque es string, no autoincremental
    protected $keyType = 'string';

    protected $fillable = [
        'numerocliente',
        'medidor',
        'rutproveedor',
        'ccosto',
        'tipo',
        'tarifa',
        'vigente',
    ];

    // Route model binding por clave primaria personalizada
    public function getRouteKeyName()
    {
        return 'numerocliente';
    }

    // Relación con Proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'rutproveedor', 'rutproveedor');
    }

    // Relación con Centro de Costo
    public function centroCosto()
        {
            return $this->belongsTo(Ccosto::class, 'ccosto', 'ccosto');
        }

    public function detalles()
        {
            return $this->hasMany(DetalleConsumoBasico::class, 'numerocliente', 'numerocliente');
        }   

}
