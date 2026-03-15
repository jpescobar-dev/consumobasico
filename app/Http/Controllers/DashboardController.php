<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dtes;
use App\Models\DetalleConsumoBasico;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Collection;


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



        $consumoxCF = DB::table('dtes')
            ->join('detalle_consumos_basicos', 'dtes.id', '=', 'detalle_consumos_basicos.dtes_id')
            ->join('clientesmedidores', 'detalle_consumos_basicos.numerocliente', '=', 'clientesmedidores.numerocliente')
            ->join('ccostos', 'clientesmedidores.ccosto', '=', 'ccostos.ccosto')
            ->join('cfinancieros', 'ccostos.cfinanciero', '=', 'cfinancieros.cfinanciero')
            ->where('dtes.RutEmisor', '=', '88272600-2')
            ->select(
                'cfinancieros.cfinanciero',
                'cfinancieros.nombre as nombre_cfinanciero',
                DB::raw('SUM(dtes.Monto) as total_monto'),
                DB::raw('SUM(detalle_consumos_basicos.consumo) as total_consumo'),
                DB::raw('COUNT(DISTINCT dtes.id) as cantidad_documentos')
            )
            ->groupBy('cfinancieros.cfinanciero', 'cfinancieros.nombre')
            ->orderBy('cfinancieros.nombre')
            ->get();
        

        $dtesConDetalles = DB::table('dtes')
            ->join('detalle_consumos_basicos', 'dtes.id', '=', 'detalle_consumos_basicos.dtes_id')
            ->join('clientesmedidores', 'detalle_consumos_basicos.numerocliente', '=', 'clientesmedidores.numerocliente')
            ->join('ccostos', 'clientesmedidores.ccosto', '=', 'ccostos.ccosto')
            ->join('cfinancieros', 'ccostos.cfinanciero', '=', 'cfinancieros.cfinanciero')
            ->select(
                'dtes.id as dte_id',
                'dtes.NumeroDte',
                'dtes.Fecha',
                'dtes.Periodo',
                'dtes.Monto',
                'dtes.RutEmisor',
                'detalle_consumos_basicos.tipo',
                'detalle_consumos_basicos.consumo',
                'detalle_consumos_basicos.numerocliente',
                'clientesmedidores.ccosto',
                'clientesmedidores.tipo as tipoconsumo',
                'ccostos.nombre as nombre_ccosto',
                'cfinancieros.cfinanciero',
                'cfinancieros.nombre as nombre_cfinanciero'
            )
            ->orderBy('dtes.Fecha', 'desc')
            ->get();    

                   

            $consumoPorTipoConsumo = DB::table('dtes')
                ->join('detalle_consumos_basicos', 'dtes.id', '=', 'detalle_consumos_basicos.dtes_id')
                ->join('clientesmedidores', 'detalle_consumos_basicos.numerocliente', '=', 'clientesmedidores.numerocliente')
                ->select(
                    'clientesmedidores.tipo as tipoconsumo',
                    DB::raw('SUM(detalle_consumos_basicos.consumo) as total_consumo')
                )
                ->groupBy('tipoconsumo')
                ->get();

                        
 // dd($consumoPorTipoConsumo);
        return view('dashboard.DashElectricidad', compact('dtes', 'meses', 'cantidadDtes', 'totalMontos', 'documentosPorTipo', 'consumoxCF', 'consumoPorTipoConsumo', 'dtesConDetalles'));
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

        return view('dashboard.DashAguaPotable', compact('dtes', 'meses', 'cantidadDtes', 'totalMontos', 'documentosPorTipo'));
    }


    public function dashDte()
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

        return view('dashboard.DashDte', compact('dtes', 'meses', 'cantidadDtes', 'totalMontos', 'documentosPorTipo'));
    }


    public function dashAlertas_2()
    {
        // 🔹 1. Calcular el promedio general de consumo energético
        $promedioConsumoEnergetico = DB::table('detalle_consumos_basicos')
            ->join('dtes', 'detalle_consumos_basicos.dtes_id', '=', 'dtes.id')
            ->selectRaw('AVG(detalle_consumos_basicos.consumo) as promedio')
            ->value('promedio');

        // 🔹 2. Obtener clientes cuyo consumo esté fuera del rango (±50% del promedio)
        $clientesFueraDeRango = DB::table('detalle_consumos_basicos')
            ->join('clientesmedidores', 'detalle_consumos_basicos.numerocliente', '=', 'clientesmedidores.numerocliente')
            ->join('dtes', 'detalle_consumos_basicos.dtes_id', '=', 'dtes.id')
            ->select(
                'clientesmedidores.numerocliente',
                'clientesmedidores.tipo',
                DB::raw('SUM(detalle_consumos_basicos.consumo) as total_consumo'),
                DB::raw("DATE_FORMAT(dtes.FechaRecepcionSII, '%Y-%m') as mes")
            )
            ->groupBy('clientesmedidores.numerocliente', 'clientesmedidores.tipo', 'mes')
            ->havingRaw('total_consumo > ? OR total_consumo < ?', [
                $promedioConsumoEnergetico * 1.15,
                $promedioConsumoEnergetico * 0.85
            ])
            ->orderBy('mes')
            ->get();

        // 🔹 3. Calcular la cantidad total de DTEs
        $cantidadTotalDtes = Dtes::count();

        // 🔹 4. Calcular la cantidad de DTEs que tienen detalle de consumo asociado
        $cantidadDtesConDetalle = DetalleConsumoBasico::count()*10;

        // 🔹 5. Calcular la cantidad de DTEs que no tienen detalle asociado
        $cantidadDtesSinDetalle = $cantidadTotalDtes - $cantidadDtesConDetalle;

        // 🔹 6. Estructurar datos para gráfica (DTEs con y sin detalle)
        $resumenDtesConYsinDetalle = [
            'con_detalle' => $cantidadDtesConDetalle,
            'sin_detalle' => $cantidadDtesSinDetalle,
        ];

        return view('dashboard.alertas_2', compact(
            'clientesFueraDeRango',
            'promedioConsumoEnergetico',
            'resumenDtesConYsinDetalle'
        ));
    }





}
