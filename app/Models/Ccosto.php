<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ccosto extends Model
{
    use HasFactory;

    protected $table = 'ccostos';
    protected $primaryKey = 'ccosto';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ccosto',
        'nombre',
        'cfinanciero',
    ];

    public function centroFinanciero(): BelongsTo
    {
        return $this->belongsTo(Cfinanciero::class, 'cfinanciero', 'cfinanciero');
    }

    public function cdps(): HasMany
    {
        return $this->hasMany(Cdp::class, 'ccosto', 'ccosto');
    }
}