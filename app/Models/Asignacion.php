<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asignacion extends Model{
   
    // La clave primaria es 'asignacion' (string)
    protected $primaryKey = 'asignacion';
     protected $table = 'asignaciones';
    public $incrementing = false; // porque es string, no autoincremental
    protected $keyType = 'string';

    protected $fillable = [
        'asignacion',
        'item',
        'nombre',
        'descripcion',
    ];

    /**
     * Relación con el modelo Item.
     * Cada asignación pertenece a un ítem.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item', 'item');
    }
}
