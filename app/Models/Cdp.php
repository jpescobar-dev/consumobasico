<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cdp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cdps';

    protected $fillable = [
        'num_cdp',
        'fecha_cdp',
        'id_sgf',
        'ccosto',
        'requerimiento',
        'descripcion',
        'moneda',
        'total_moneda_compra',
        'fechaparidad',
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
        'fechaparidad' => 'date',
        'validez' => 'date',
        'total_moneda_compra' => 'decimal:2',
        'paridad' => 'decimal:4',
        'monto_total_impto_incluido' => 'decimal:2',
        'total_compromiso' => 'decimal:2',
        'cargado_cgu' => 'boolean',
        'comprometido_cgu' => 'boolean',
        'pp' => 'integer',
        'proyecto_id' => 'integer',
        'estado_id' => 'integer',
    ];

    public function centroCosto(): BelongsTo
    {
        return $this->belongsTo(Ccosto::class, 'ccosto', 'ccosto');
    }

    public function catalogoRelacion(): BelongsTo
    {
        return $this->belongsTo(Catalogo::class, 'catalogo', 'catalogo');
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function paridadUf(): BelongsTo
    {
        return $this->belongsTo(ParidadUf::class, 'fechaparidad', 'fecha');
    }
}