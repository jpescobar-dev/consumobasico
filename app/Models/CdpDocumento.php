<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CdpDocumento extends Model
{
    use HasFactory;

    protected $table = 'cdp_documentos';

    protected $fillable = [
        'cdp_id',
        'nombre_original',
        'archivo',
        'mime_type',
        'peso',
        'observacion',
    ];

    public function cdp()
    {
        return $this->belongsTo(Cdp::class, 'cdp_id', 'id');
    }
}