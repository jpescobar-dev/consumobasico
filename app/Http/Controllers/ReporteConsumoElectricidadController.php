<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteConsumoElectricidadController extends Controller
{
    public function index(Request $request)
    {
        $yearExpr = "
            CASE
                WHEN d.Periodo REGEXP '^[0-9]{4}-[0-9]{2}$' THEN LEFT(d.Periodo, 4)
                WHEN d.Periodo REGEXP '^[0-9]{6}$' THEN LEFT(d.Periodo, 4)
                WHEN d.Periodo REGEXP '^[0-9]{1,2}/[0-9]{4}$' THEN RIGHT(d.Periodo, 4)
                WHEN d.Periodo REGEXP '^[0-9]{1,2}-[0-9]{4}$' THEN RIGHT(d.Periodo, 4)
                ELSE NULL
            END
        ";

        $monthExpr = "
            CASE
                WHEN d.Periodo REGEXP '^[0-9]{4}-[0-9]{2}$' THEN CAST(RIGHT(d.Periodo, 2) AS UNSIGNED)
                WHEN d.Periodo REGEXP '^[0-9]{6}$' THEN CAST(RIGHT(d.Periodo, 2) AS UNSIGNED)
                WHEN d.Periodo REGEXP '^[0-9]{1,2}/[0-9]{4}$' THEN CAST(SUBSTRING_INDEX(d.Periodo, '/', 1) AS UNSIGNED)
                WHEN d.Periodo REGEXP '^[0-9]{1,2}-[0-9]{4}$' THEN CAST(SUBSTRING_INDEX(d.Periodo, '-', 1) AS UNSIGNED)
                ELSE NULL
            END
        ";

        $meses = [
            'enero',
            'febrero',
            'marzo',
            'abril',
            'mayo',
            'junio',
            'julio',
            'agosto',
            'septiembre',
            'octubre',
            'noviembre',
            'diciembre',
        ];

        $aniosDisponibles = DB::table('dtes as d')
            ->join('detalle_consumos_basicos as det', 'd.id', '=', 'det.dtes_id')
            ->whereNotNull('d.Periodo')
            ->whereRaw("{$yearExpr} IS NOT NULL")
            ->selectRaw("DISTINCT {$yearExpr} as anio")
            ->orderByDesc('anio')
            ->pluck('anio');

        $anio = $request->get('anio', $aniosDisponibles->first() ?? date('Y'));

        $detalleUnico = DB::table('detalle_consumos_basicos as det')
            ->select('det.dtes_id', 'det.numerocliente')
            ->distinct();

        $reporte = DB::table('dtes as d')
            ->joinSub($detalleUnico, 'du', function ($join) {
                $join->on('d.id', '=', 'du.dtes_id');
            })
            ->join('clientesmedidores as cm', 'du.numerocliente', '=', 'cm.numerocliente')
            ->join('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
            ->leftJoin('cfinancieros as cf', 'cc.cfinanciero', '=', 'cf.cfinanciero')
            ->whereRaw("{$yearExpr} = ?", [$anio])
            ->selectRaw("
                cf.cfinanciero as cfinanciero,
                cf.nombre as nombre_cfinanciero,
                du.numerocliente as numerocliente,
                cm.ccosto as centro_costo,
                cc.nombre as juzgado,
                cm.tipo as tipo,

                SUM(CASE WHEN {$monthExpr} = 1 THEN d.Monto ELSE 0 END) as enero,
                SUM(CASE WHEN {$monthExpr} = 2 THEN d.Monto ELSE 0 END) as febrero,
                SUM(CASE WHEN {$monthExpr} = 3 THEN d.Monto ELSE 0 END) as marzo,
                SUM(CASE WHEN {$monthExpr} = 4 THEN d.Monto ELSE 0 END) as abril,
                SUM(CASE WHEN {$monthExpr} = 5 THEN d.Monto ELSE 0 END) as mayo,
                SUM(CASE WHEN {$monthExpr} = 6 THEN d.Monto ELSE 0 END) as junio,
                SUM(CASE WHEN {$monthExpr} = 7 THEN d.Monto ELSE 0 END) as julio,
                SUM(CASE WHEN {$monthExpr} = 8 THEN d.Monto ELSE 0 END) as agosto,
                SUM(CASE WHEN {$monthExpr} = 9 THEN d.Monto ELSE 0 END) as septiembre,
                SUM(CASE WHEN {$monthExpr} = 10 THEN d.Monto ELSE 0 END) as octubre,
                SUM(CASE WHEN {$monthExpr} = 11 THEN d.Monto ELSE 0 END) as noviembre,
                SUM(CASE WHEN {$monthExpr} = 12 THEN d.Monto ELSE 0 END) as diciembre,

                SUM(d.Monto) as total_anual
            ")
            ->groupBy(
                'cf.cfinanciero',
                'cf.nombre',
                'du.numerocliente',
                'cm.ccosto',
                'cc.nombre',
                'cm.tipo'
            )
            ->orderByRaw("COALESCE(cf.nombre, 'ZZZ')")
            ->orderBy('cm.ccosto')
            ->orderBy('du.numerocliente')
            ->get()
            ->map(function ($row) {
                $row->cfinanciero = $row->cfinanciero ?: 'SIN_CF';
                $row->nombre_cfinanciero = $row->nombre_cfinanciero ?: 'SIN CENTRO FINANCIERO';
                return $row;
            });

        $gruposCentroFinanciero = $reporte
            ->groupBy('cfinanciero')
            ->map(function ($items) use ($meses) {
                $primero = $items->first();

                $centrosCosto = $items
                    ->groupBy('centro_costo')
                    ->map(function ($grupoCc) {
                        $primeroCc = $grupoCc->first();

                        return [
                            'centro_costo' => $primeroCc->centro_costo,
                            'juzgado' => $primeroCc->juzgado,
                            'items' => $grupoCc->sortBy('numerocliente')->values(),
                        ];
                    })
                    ->values();

                $subtotal = [];
                foreach ($meses as $mes) {
                    $subtotal[$mes] = $items->sum($mes);
                }
                $subtotal['total_anual'] = $items->sum('total_anual');

                return [
                    'cfinanciero' => $primero->cfinanciero,
                    'nombre_cfinanciero' => $primero->nombre_cfinanciero,
                    'centros_costo' => $centrosCosto,
                    'subtotal' => $subtotal,
                ];
            })
            ->values();

        $totales = [
            'enero'       => $reporte->sum('enero'),
            'febrero'     => $reporte->sum('febrero'),
            'marzo'       => $reporte->sum('marzo'),
            'abril'       => $reporte->sum('abril'),
            'mayo'        => $reporte->sum('mayo'),
            'junio'       => $reporte->sum('junio'),
            'julio'       => $reporte->sum('julio'),
            'agosto'      => $reporte->sum('agosto'),
            'septiembre'  => $reporte->sum('septiembre'),
            'octubre'     => $reporte->sum('octubre'),
            'noviembre'   => $reporte->sum('noviembre'),
            'diciembre'   => $reporte->sum('diciembre'),
            'total_anual' => $reporte->sum('total_anual'),
        ];

        return view('reportes.consumo-electricidad.index', compact(
            'reporte',
            'gruposCentroFinanciero',
            'anio',
            'aniosDisponibles',
            'totales'
        ));
    }
}