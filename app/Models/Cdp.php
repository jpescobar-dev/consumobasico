<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cdp extends Model
{
    use HasFactory;

    protected $fillable = [
        'num_cdp',
        'fecha_cdp',
        'id_proceso',
        'cfinanciero_id',
        'requerimiento',
        'descripcion',
        'ccosto',
        'moneda',
        'total_moneda_compra',
        'paridad',
        'monto_total_impto_incluido',
        'st',
        'catalogo_id',
        'denominacion',
        'tipo_gasto1',
        'proyecto_id',
        'pp',
        'tipo_gasto2',
        'cargado_cgu',
        'comprometido_cgu',
        'total_compromiso',
        'num_compromiso',
        'observaciones',
        'validez',
    ];

    public function ccosto()
    {
        return $this->belongsTo(Ccosto::class);
    }
}
