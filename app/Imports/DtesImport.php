<?php

namespace App\Imports;

use App\Models\Dtes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DtesImport implements ToModel, WithHeadingRow
{

    protected $errors = [];
    protected $importedData = [];
    /**
     * Define cómo se debe importar cada fila.
     */
    public function model(array $row)
    {
        // Normalizar claves eliminando espacios y usando minúsculas
        $normalizedRow = array_change_key_case(array_map('trim', $row), CASE_LOWER);

        // Verificar si idRedFlow realmente tiene datos
        if (!isset($normalizedRow['idredflow']) || empty($normalizedRow['idredflow'])) {
            logger()->error('Error: idRedFlow es NULL o no existe en la fila', $normalizedRow);
            return null; // Evita la inserción de filas corruptas
        }

        // Convertir valores vacíos en NULL o valores por defecto
        $normalizedRow['egreso'] = empty($normalizedRow['egreso']) ? 0 : $normalizedRow['egreso'];
        $normalizedRow['monto'] = empty($normalizedRow['monto']) ? 0 : $normalizedRow['monto'];

        // Manejo de fechas para evitar errores en la importación
        $normalizedRow['fecha'] = isset($normalizedRow['fecha']) && !empty($normalizedRow['fecha'])
            ? (is_numeric($normalizedRow['fecha']) 
                ? Carbon::instance(Date::excelToDateTimeObject($normalizedRow['fecha']))->format('Y-m-d H:i:s') 
                : Carbon::parse($normalizedRow['fecha'])->format('Y-m-d H:i:s'))
            : null;

        $normalizedRow['fecharecepcionsii'] = isset($normalizedRow['fecharecepcionsii']) && !empty($normalizedRow['fecharecepcionsii'])
            ? (is_numeric($normalizedRow['fecharecepcionsii']) 
                ? Carbon::instance(Date::excelToDateTimeObject($normalizedRow['fecharecepcionsii']))->format('Y-m-d H:i:s') 
                : Carbon::parse($normalizedRow['fecharecepcionsii'])->format('Y-m-d H:i:s'))
            : null;

             // Quitar puntos del RutEmisor si existe
       if (isset($normalizedRow['rutemisor'])) {
        $normalizedRow['rutemisor'] = str_replace('.', '', $normalizedRow['rutemisor']);
        }

        // Verificar si el registro ya existe en la base de datos
        $exists = Dtes::where('NumeroDte', $normalizedRow['numerodte'])
            ->where('RutEmisor', $normalizedRow['rutemisor'])
            ->exists();
      

        if ($exists) {
            $this->errors[] = 'El documento con Número DTE: ' . $normalizedRow['numerodte'] . ' y Rut Emisor: ' . $normalizedRow['rutemisor'] . ' ya existe en la base de datos.';
            return null; // Omitir solo esta fila
        }

        
        // Depurar los valores antes de insertar (puedes desactivarlo si ya funciona)

        // logger()->info('Fila procesada correctamente', $normalizedRow);     

       $this->importedData[] = $normalizedRow;

        return new Dtes([
            'idRedFlow' => $normalizedRow['idredflow'],
            'Periodo' => $normalizedRow['periodo'],
            'Fecha' => $normalizedRow['fecha'],
            'FechaRecepcionSII' => $normalizedRow['fecharecepcionsii'],
            'NumeroDte' => $normalizedRow['numerodte'],
            'RutEmisor' => $normalizedRow['rutemisor'],
            'Observacion' => $normalizedRow['observacion'],
            'Monto' => $normalizedRow['monto'],
            'Egreso' => $normalizedRow['egreso'],
            'TipoDcto' => $normalizedRow['tipodcto'],
            'NombreEmisor' => $normalizedRow['nombreemisor'],
            'Url' => $normalizedRow['url'],
            'Estado' => 'Importado', // Valor por defecto
            'FechaImportacion' => Carbon::now()->format('Y-m-d H:i:s'), // Fecha actual
        ]);
    }
    

    
}



