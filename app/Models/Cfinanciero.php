<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cfinanciero extends Model
{
    use HasFactory;

    protected $table = 'cfinancieros';
    protected $primaryKey = 'cfinanciero';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cfinanciero',
        'nombre',
    ];

    public function centrosCosto(): HasMany
    {
        return $this->hasMany(Ccosto::class, 'cfinanciero', 'cfinanciero');
    }
}