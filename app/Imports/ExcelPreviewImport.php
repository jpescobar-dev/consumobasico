<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class ExcelPreviewImport implements ToCollection, WithStartRow
{
    public array $headers = [];
    public Collection $rows;

    // Campos que deben tratarse como fechas
    protected array $camposFecha = [
        'fecha',
        'fecha recepción sii',
        'fecha creación',
    ];

    public function startRow(): int
    {
        return 7; // Comenzar desde la fila 7
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            $this->headers = [];
            $this->rows = collect();
            return;
        }

        // Leer encabezados desde la fila 7, columnas B a V (21 columnas)
        $this->headers = collect($rows->first()->slice(1, 21))
            ->map(function ($value, $key) {
                $header = trim(strtolower((string) $value)) ?: 'col_' . $key;

                // Normalizar nombres de columnas específicas
                $replacements = [
                    'n° egreso' => 'egreso',
                    'número egreso' => 'egreso',
                    'numero egreso' => 'egreso',
                    'n egreso' => 'egreso',
                ];

                return $replacements[$header] ?? $header;
            })
            ->toArray();

        // Procesar cada fila desde la fila 8 en adelante
        $this->rows = $rows->slice(1)->map(function ($row) {
            $values = collect($row)->slice(1, 21)->toArray();

            if (count($values) !== count($this->headers)) {
                return []; // O manejar el error si quieres
            }

            $fila = array_combine($this->headers, $values);

            foreach ($fila as $key => $value) {
                $keyLower = strtolower($key);

                // ✔️ Normalizar fechas
                if (in_array($keyLower, $this->camposFecha)) {
                    try {
                        if (is_numeric($value)) {
                            $fecha = Carbon::createFromDate(1900, 1, 1)->addDays((int)$value - 2);
                            if (fmod($value, 1) > 0) {
                                $fecha->addMinutes(round(fmod($value, 1) * 1440));
                                $fila[$key] = $fecha->format('d-m-Y H:i');
                            } else {
                                $fila[$key] = $fecha->format('d-m-Y');
                            }
                        } else {
                            $fila[$key] = Carbon::parse($value)->format('d-m-Y');
                        }
                    } catch (\Exception $e) {
                        // Ignorar errores de fecha
                    }
                }

                // ✔️ Normalizar RUT
                if ($keyLower === 'rut') {
                    $fila[$key] = str_replace('.', '', $value);
                }

                // ✔️ Parsear campo Observación
                if (in_array($keyLower, ['observación', 'observacion'])) {
                    $value = preg_replace("/\s+/", ' ', trim($value));
                    $fila['observacion'] = $value;
                    unset($fila[$key]);

                    // Inicializa todos los campos derivados para que siempre existan
                    $fila['tipodocumento'] = null;
                    $fila['numerodocumento'] = null;
                    $fila['formaingreso'] = null;
                    $fila['rutproveedor'] = null;
                    $fila['nombreproveedor'] = null;
                    $fila['url'] = null;

                    // Regex más tolerante para extraer datos
                    $pattern = '/^([A-Z]+)\s+(\d+)\s+(.+?)\.?\s+Emisor\s+([\d\.]+-[\dkK]):\s+(.+?)\.?\s+(https?:\/\/\S+)/i';

                    if (preg_match($pattern, $value, $matches)) {
                        $fila['tipodocumento']   = trim($matches[1]);
                        $fila['numerodocumento'] = trim($matches[2]);
                        $fila['formaingreso']    = trim($matches[3]);
                        $fila['rutproveedor']    = str_replace('.', '', trim($matches[4]));
                        $fila['nombreproveedor'] = trim($matches[5]);
                        $fila['url']             = trim($matches[6]);
                    }
                }
            }

            return $fila;
        })->filter(); // Eliminar filas vacías o inválidas
    }
}
