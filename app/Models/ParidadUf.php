<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParidadUf extends Model
{
    use HasFactory;

    protected $table = 'paridad_ufs';

    protected $fillable = [
        'fecha',
        'valor',
    ];

    protected $casts = [
        'fecha' => 'date',
        'valor' => 'decimal:4',
    ];

    public function cdps(): HasMany
    {
        return $this->hasMany(Cdp::class, 'fechaparidad', 'fecha');
    }
}