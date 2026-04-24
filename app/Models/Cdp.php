<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cdp extends Model
{
    use HasFactory;

    protected $table = 'cdps';

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
        'fecha_paridad',
        'paridad',
        'monto_total_impto_incluido',
        'st',
        'catalogo',
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
        'estado_id',
    ];

    protected $casts = [
        'fecha_cdp' => 'date',
        'fecha_paridad' => 'date',
        'cargado_cgu' => 'boolean',
        'comprometido_cgu' => 'boolean',
        'total_moneda_compra' => 'decimal:4',
        'paridad' => 'decimal:4',
        'monto_total_impto_incluido' => 'decimal:0',
        'total_compromiso' => 'decimal:2',
    ];

    public function cfinanciero()
    {
        return $this->belongsTo(Cfinanciero::class, 'cfinanciero_id', 'cfinanciero');
    }

    public function centroCosto()
    {
        return $this->belongsTo(Ccosto::class, 'ccosto', 'ccosto');
    }

    public function catalogoRelacion()
    {
        return $this->belongsTo(Catalogo::class, 'catalogo', 'catalogo');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id', 'id');
    }

    public function documentos()
    {
        return $this->hasMany(CdpDocumento::class, 'cdp_id', 'id');
    }

    // Compatibilidad con vistas/controladores que aún usan proceso_sgf
    public function getProcesoSgfAttribute()
    {
        return $this->id_proceso;
    }
}