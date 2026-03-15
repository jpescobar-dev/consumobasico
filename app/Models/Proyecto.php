<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos'; // Nombre de la tabla

    protected $fillable = [
        'proyecto',
        'descripcion',
        'codigo',
        'fecha_inicio',
        'fecha_termino',
        'avance',
        'monto_estimado',
        'monto_asignado',
        'cfinanciero_id',
        'estado_id',
    ];

    
    public function centroFinanciero()
    {
        return $this->belongsTo(Cfinanciero::class, 'cfinanciero_id');
    }

    
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function cdps(): HasMany
    {
        return $this->hasMany(Cdp::class, 'proyecto_id');
    }
}
