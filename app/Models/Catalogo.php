<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Catalogo extends Model
{
    use HasFactory;

    protected $table = 'catalogos';

    protected $primaryKey = 'catalogo';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'catalogo',
        'nombre',
        'descripcion',
        'estado',
        'item',
    ];

    public function getRouteKeyName()
    {
        return 'catalogo';
    }

    // Relación con Item (N:1)
    public function item()
    {
        return $this->belongsTo(Item::class, 'item', 'item');
    }    
   
}
