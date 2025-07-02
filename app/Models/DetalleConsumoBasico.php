<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleConsumoBasico extends Model
{
    use HasFactory;

    // Nombre de la tabla explícito
    protected $table = 'detalle_consumos_basicos';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'dtes_id',
        'tipo',
        'numerocliente',
        'periodoconsumo',
        'lecturaanterior',
        'lecturaactual',
        'consumo',
    ];

    // Relación inversa hacia Dte
    public function dte()
    {
        return $this->belongsTo(Dtes::class, 'dtes_id');
    }
    
}
