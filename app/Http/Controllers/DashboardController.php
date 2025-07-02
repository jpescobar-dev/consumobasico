<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dtes;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener los datos para la vista
        $dtes = Dtes::all();

        // Obtener los meses y cantidad de documentos por mes
        $meses = Dtes::selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('mes');

        $cantidadDtes = Dtes::selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes, COUNT(*) as cantidad")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('cantidad');

        // Obtener el total de montos por mes
        $totalMontos = Dtes::selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes, SUM(Monto) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total');

        // Obtener totales agrupados por tipo de documento
        $documentosPorTipo = Dtes::selectRaw('TipoDcto, COUNT(*) as total')
            ->groupBy('TipoDcto')
            ->get();

        return view('dashboard.index', compact('dtes', 'meses', 'cantidadDtes', 'totalMontos', 'documentosPorTipo'));
    }



     public function dashElectricidad()
    {
        // Obtener los datos para la vista
        $dtes = Dtes::where('RutEmisor', '88272600-2')->get();

// dd($dtes);
        // Obtener los meses y cantidad de documentos por mes
        $meses = Dtes::where('RutEmisor', '88272600-2')
            ->selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('mes');
           

        $cantidadDtes = Dtes::where('RutEmisor', '88272600-2')
            ->selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes, COUNT(*) as cantidad")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('cantidad');
           

        // Obtener el total de montos por mes
        $totalMontos = Dtes::where('RutEmisor', '88272600-2')->selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes, SUM(Monto) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total');

        // Obtener totales agrupados por tipo de documento
        $documentosPorTipo = Dtes::where('RutEmisor', '88272600-2')
            ->selectRaw('TipoDcto, COUNT(*) as total')
            ->groupBy('TipoDcto')
            ->get();

        return view('dashboard.electricidad', compact('dtes', 'meses', 'cantidadDtes', 'totalMontos', 'documentosPorTipo'));
    }


    public function dashAguaPotable()
    {
        // Obtener los datos para la vista
        $dtes = Dtes::where('RutEmisor', '99501280-4')->get();


        // Obtener los meses y cantidad de documentos por mes
        $meses = Dtes::where('RutEmisor', '99501280-4')
            ->selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('mes');
           

        $cantidadDtes = Dtes::where('RutEmisor', '99501280-4')
            ->selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes, COUNT(*) as cantidad")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('cantidad');
           

        // Obtener el total de montos por mes
        $totalMontos = Dtes::where('RutEmisor', '99501280-4')->selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes, SUM(Monto) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total');

        // Obtener totales agrupados por tipo de documento
        $documentosPorTipo = Dtes::where('RutEmisor', '99501280-4')
            ->selectRaw('TipoDcto, COUNT(*) as total')
            ->groupBy('TipoDcto')
            ->get();

        return view('dashboard.aguapotable', compact('dtes', 'meses', 'cantidadDtes', 'totalMontos', 'documentosPorTipo'));
    }
}
