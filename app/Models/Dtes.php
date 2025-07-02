<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dtes extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'dtes';

    // Laravel asume que la clave primaria es "id", si es diferente, debes especificarlo
    protected $primaryKey = 'id';

    // Si el ID no es autoincremental, indícalo
    public $incrementing = true;

    // Si la tabla no tiene created_at y updated_at
    public $timestamps = false;

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'idRedFlow',
        'Periodo',
        'Fecha',
        'FechaRecepcionSII',
        'NumeroDte',
        'RutEmisor',
        'Observacion',
        'Monto',
        'Egreso',
        'TipoDcto',
        'NombreEmisor',
        'Url',
        'Estado', // Nueva columna
        'FechaImportacion', // Nueva columna
    ];
    // Definir los tipos de datos para cada campo (corrigiendo nombres)
    protected $casts = [
        'Fecha' => 'datetime',
        'FechaRecepcionSII' => 'datetime',
        'Monto' => 'decimal:0',
        'Egreso' => 'decimal:0',
    ];

    
    
    // Ejemplo de relación (ajústala si aplica)
    public function emisor()
    {
        return $this->belongsTo(User::class, 'RutEmisor', 'rut');
    }
   

    // Relación hacia detalle de consumo básico
    public function consumoBasico()
    {
        return $this->hasOne(DetalleConsumoBasico::class, 'dtes_id');
    }




    
}
