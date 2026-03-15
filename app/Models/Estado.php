<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'estados';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tabla_referencia',
    ];

    public function cdps(): HasMany
    {
        return $this->hasMany(Cdp::class, 'estado_id');
    }

    
}
