<?php

namespace App\Http\Controllers;

use App\Imports\ExcelPreviewImport;
use App\Models\Dtes;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ExcelPreviewController extends Controller
{
    public function showForm()
        {
            return view('excel.import-form');
        }

    public function import(Request $request)
        {
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls'
            ]);
        
            try {
                $import = new ExcelPreviewImport();
                Excel::import($import, $request->file('excel_file'));
        
                $headers = $import->headers;
                $rows = $import->rows;
        
                // Clasificar registros
                $validos = collect();
                $rechazados = collect();
        
                foreach ($rows as $row) {
                    $idRedFlow = $row['id'] ?? null;
                    $rut = $row['rutproveedor'] ?? null;
                    $numero = $row['numerodocumento'] ?? null;
        
                    if (!$idRedFlow || !$rut || !$numero) {
                        $rechazados->push($row);
                    } else {
                        $validos->push($row);
                    }
                }
        
                session([
                    'import_headers' => $headers,
                    'import_validos' => $validos,
                    'import_rechazados' => $rechazados,
                ]);
        
                return view('excel.preview', [
                    'headers' => $headers,
                    'validos' => $validos,
                    'rechazados' => $rechazados,
                ]);
        
            } catch (\Throwable $e) {
                return back()->withErrors([
                    'excel_file' => 'Hubo un error al procesar el archivo: ' . $e->getMessage()
                ]);
            }
        }
    
    public function cancel()
        {
            session()->forget(['import_headers', 'import_validos', 'import_rechazados']);
            return redirect()->route('excel.import-form')->with('info', 'Importación cancelada.');
        }
    
        public function store(Request $request)
{
    $validos = session('import_validos', collect());

    if ($validos->isEmpty()) {
        return redirect()->route('excel.import-form')->with('error', 'No hay datos para guardar.');
    }

    foreach ($validos as $row) {
        $idRedFlow = $row['id'] ?? null;
        $rut = $row['rutproveedor'] ?? null;
        $numero = $row['numerodocumento'] ?? null;

        $estado = (empty($row['egreso']) || $row['egreso'] == 0) ? 2 : 9;

        if (Dtes::where('idRedFlow', $idRedFlow)->exists() ||
            Dtes::where('RutEmisor', $rut)->where('NumeroDte', $numero)->exists()) {
            continue;
        }

        Dtes::create([
            'idRedFlow' => $idRedFlow,
            'Periodo' => $row['periodo'] ?? '',
            'Fecha' => isset($row['fecha']) ? Carbon::parse($row['fecha']) : null,
            'FechaRecepcionSII' => isset($row['fecha recepción sii']) ? Carbon::parse($row['fecha recepción sii']) : null,
            'NumeroDte' => $numero,
            'RutEmisor' => $rut,
            'Observacion' => $row['observacion'] ?? '',
            'Monto' => $row['monto'] ?? 0,
            'Egreso' => $row['egreso'] ?? null,
            'TipoDcto' => $row['tipodocumento'] ?? '',
            'NombreEmisor' => $row['nombreproveedor'] ?? '',
            'Url' => $row['url'] ?? '',
            'Estado' => $estado,
            'FechaImportacion' => now(),
        ]);

        if (!empty($rut) && !empty($row['nombreproveedor'])) {
            Proveedor::updateOrCreate(
                ['rutproveedor' => $rut],
                ['nombre' => $row['nombreproveedor']]
            );
        }
    }

    session()->forget(['import_headers', 'import_validos', 'import_rechazados']);

    // return redirect()->route('excel.import-form')->with('success', 'Datos importados exitosamente.');
    return redirect()->route('dtes.index')->with('success', 'Datos importados exitosamente.');

}




}

