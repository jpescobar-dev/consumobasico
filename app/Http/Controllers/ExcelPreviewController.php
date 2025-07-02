<?php

namespace App\Http\Controllers;

use App\Imports\ExcelPreviewImport;
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
        $import = new ExcelPreviewImport;
        Excel::import($import, $request->file('excel_file'));

        $headers = $import->headers;
        $rows = $import->rows;

        return view('excel.preview', compact('headers', 'rows'));

    } catch (\Throwable $e) {
        return back()->withErrors([
            'excel_file' => 'Hubo un error al procesar el archivo: ' . $e->getMessage()
        ]);
    }
}



}

