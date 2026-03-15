<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function itemRelacion(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item', 'item');
    }

    public function cdps(): HasMany
    {
        return $this->hasMany(Cdp::class, 'catalogo', 'catalogo');
    }
}