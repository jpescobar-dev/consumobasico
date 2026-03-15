<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Licitacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'licitaciones';

    protected $primaryKey = 'numero_licitacion';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'numero_licitacion',
        'nombre',
        'descripcion',
        'estado',
        'tipo',
        'unidad_compra',
        'monto_total_estimado',
        'numero_ofertas_recibidas',
        'fecha_publicacion',
        'fecha_adjudicacion',
        ];   

    protected $casts = [
    'fecha_publicacion' => 'datetime',
    'fecha_adjudicacion' => 'datetime',
    'deleted_at' => 'datetime',
    ];


    public function getRouteKeyName()
    {
        return 'numero_licitacion';
    }
}
