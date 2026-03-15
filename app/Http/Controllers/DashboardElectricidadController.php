<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dtes;
use App\Models\DetalleConsumoBasico;
use Illuminate\Support\Facades\DB;

class DashboardElectricidadController extends Controller
{
    public function dashElectricidad()
    {
        $rutEmisor = '88272600-2';

        /*
        |--------------------------------------------------------------------------
        | Base documentos
        |--------------------------------------------------------------------------
        */
        $dtes = Dtes::where('RutEmisor', $rutEmisor)
            ->orderByDesc('FechaRecepcionSII')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Consultas base históricas
        |--------------------------------------------------------------------------
        */
        $meses = Dtes::where('RutEmisor', $rutEmisor)
            ->selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('mes');

        $cantidadDtes = Dtes::where('RutEmisor', $rutEmisor)
            ->selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes, COUNT(*) as cantidad")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('cantidad');

        $totalMontos = Dtes::where('RutEmisor', $rutEmisor)
            ->selectRaw("DATE_FORMAT(FechaRecepcionSII, '%Y-%m') as mes, SUM(Monto) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total');

        $documentosPorTipo = Dtes::where('RutEmisor', $rutEmisor)
            ->selectRaw('TipoDcto, COUNT(*) as total')
            ->groupBy('TipoDcto')
            ->orderBy('TipoDcto')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Subconsultas base para evitar duplicidades
        |--------------------------------------------------------------------------
        */
        $consumoTotalPorDte = DB::table('detalle_consumos_basicos as det')
            ->select(
                'det.dtes_id',
                DB::raw('SUM(det.consumo) as total_consumo_dte')
            )
            ->groupBy('det.dtes_id');

        $consumoPorDte = DB::table('detalle_consumos_basicos as det')
            ->select(
                'det.dtes_id',
                DB::raw('SUM(det.consumo) as total_consumo_kw')
            )
            ->groupBy('det.dtes_id');

        /*
        |--------------------------------------------------------------------------
        | Gráfico principal
        | X: Periodo
        | Barras: monto mensual
        | Línea: consumo kW
        |--------------------------------------------------------------------------
        */
        $periodoExpr = "COALESCE(NULLIF(d.Periodo, ''), DATE_FORMAT(d.FechaRecepcionSII, '%Y-%m'))";

        $graficoConsumoPeriodo = DB::table('dtes as d')
            ->leftJoinSub($consumoPorDte, 'cd', function ($join) {
                $join->on('d.id', '=', 'cd.dtes_id');
            })
            ->where('d.RutEmisor', $rutEmisor)
            ->selectRaw("
                {$periodoExpr} as periodo_consumo,
                MIN(d.FechaRecepcionSII) as fecha_orden,
                COUNT(DISTINCT d.id) as cantidad_documentos,
                SUM(d.Monto) as total_pesos,
                SUM(COALESCE(cd.total_consumo_kw, 0)) as total_kw
            ")
            ->groupByRaw($periodoExpr)
            ->orderBy('fecha_orden')
            ->get();

        $chartPeriodoLabels = $graficoConsumoPeriodo
            ->pluck('periodo_consumo')
            ->values();

        $chartMontoMensual = $graficoConsumoPeriodo
            ->pluck('total_pesos')
            ->map(fn ($v) => (float) $v)
            ->values();

        $chartConsumoKw = $graficoConsumoPeriodo
            ->pluck('total_kw')
            ->map(fn ($v) => (float) $v)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Métricas resumen
        |--------------------------------------------------------------------------
        */
        $totalDtes = Dtes::where('RutEmisor', $rutEmisor)->count();

        $totalMontoDashboard = Dtes::where('RutEmisor', $rutEmisor)->sum('Monto');

        $totalConsumoDashboard = DB::table('detalle_consumos_basicos as det')
            ->join('dtes as d', 'det.dtes_id', '=', 'd.id')
            ->where('d.RutEmisor', $rutEmisor)
            ->sum('det.consumo');

        $metricasDashboard = [
            'total_dtes'              => (int) $totalDtes,
            'total_monto'             => (float) $totalMontoDashboard,
            'total_consumo_kw'        => (float) $totalConsumoDashboard,
            'promedio_monto_por_dte'  => $totalDtes > 0 ? round($totalMontoDashboard / $totalDtes, 2) : 0,
            'promedio_kw_por_periodo' => $graficoConsumoPeriodo->count() > 0
                ? round($graficoConsumoPeriodo->avg('total_kw'), 2)
                : 0,
            'cantidad_periodos'       => $graficoConsumoPeriodo->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Consumo por Centro Financiero (con prorrateo de monto)
        |--------------------------------------------------------------------------
        */
        $consumoPorCfYDte = DB::table('detalle_consumos_basicos as det')
            ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
            ->join('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
            ->join('cfinancieros as cf', 'cc.cfinanciero', '=', 'cf.cfinanciero')
            ->select(
                'det.dtes_id',
                'cf.cfinanciero',
                'cf.nombre as nombre_cfinanciero',
                DB::raw('SUM(det.consumo) as consumo_cf_dte')
            )
            ->groupBy(
                'det.dtes_id',
                'cf.cfinanciero',
                'cf.nombre'
            );

        $consumoxCF = DB::table('dtes as d')
            ->joinSub($consumoPorCfYDte, 'pcf', function ($join) {
                $join->on('d.id', '=', 'pcf.dtes_id');
            })
            ->leftJoinSub($consumoTotalPorDte, 'ptd', function ($join) {
                $join->on('d.id', '=', 'ptd.dtes_id');
            })
            ->where('d.RutEmisor', $rutEmisor)
            ->select(
                'pcf.cfinanciero',
                'pcf.nombre_cfinanciero',
                DB::raw("
                    SUM(
                        CASE
                            WHEN COALESCE(ptd.total_consumo_dte, 0) > 0
                            THEN d.Monto * (pcf.consumo_cf_dte / ptd.total_consumo_dte)
                            ELSE 0
                        END
                    ) as total_monto
                "),
                DB::raw('SUM(pcf.consumo_cf_dte) as total_consumo'),
                DB::raw('COUNT(DISTINCT d.id) as cantidad_documentos')
            )
            ->groupBy('pcf.cfinanciero', 'pcf.nombre_cfinanciero')
            ->orderBy('pcf.nombre_cfinanciero')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Consumo por Centro de Costos (donut) con prorrateo de monto
        |--------------------------------------------------------------------------
        */
        $consumoPorCcostoYDte = DB::table('detalle_consumos_basicos as det')
            ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
            ->join('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
            ->select(
                'det.dtes_id',
                'cc.ccosto',
                'cc.nombre as nombre_ccosto',
                DB::raw('SUM(det.consumo) as consumo_ccosto_dte')
            )
            ->groupBy(
                'det.dtes_id',
                'cc.ccosto',
                'cc.nombre'
            );

        $consumoPorCentroCosto = DB::table('dtes as d')
            ->joinSub($consumoPorCcostoYDte, 'pcc', function ($join) {
                $join->on('d.id', '=', 'pcc.dtes_id');
            })
            ->leftJoinSub($consumoTotalPorDte, 'ptd', function ($join) {
                $join->on('d.id', '=', 'ptd.dtes_id');
            })
            ->where('d.RutEmisor', $rutEmisor)
            ->select(
                'pcc.ccosto',
                'pcc.nombre_ccosto',
                DB::raw('SUM(pcc.consumo_ccosto_dte) as total_consumo'),
                DB::raw("
                    SUM(
                        CASE
                            WHEN COALESCE(ptd.total_consumo_dte, 0) > 0
                            THEN d.Monto * (pcc.consumo_ccosto_dte / ptd.total_consumo_dte)
                            ELSE 0
                        END
                    ) as total_monto
                "),
                DB::raw('COUNT(DISTINCT d.id) as cantidad_documentos')
            )
            ->groupBy('pcc.ccosto', 'pcc.nombre_ccosto')
            ->orderBy('pcc.nombre_ccosto')
            ->get();

        $chartCentroCostoLabels = $consumoPorCentroCosto
            ->pluck('nombre_ccosto')
            ->values();

        $chartCentroCostoSeries = $consumoPorCentroCosto
            ->pluck('total_consumo')
            ->map(fn ($v) => (float) $v)
            ->values();

        $chartCentroCostoMontos = $consumoPorCentroCosto
            ->pluck('total_monto')
            ->map(fn ($v) => (float) $v)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Consumo por tipo de consumo
        |--------------------------------------------------------------------------
        */
        $consumoPorTipoConsumo = DB::table('dtes')
            ->join('detalle_consumos_basicos', 'dtes.id', '=', 'detalle_consumos_basicos.dtes_id')
            ->join('clientesmedidores', 'detalle_consumos_basicos.numerocliente', '=', 'clientesmedidores.numerocliente')
            ->where('dtes.RutEmisor', $rutEmisor)
            ->select(
                'clientesmedidores.tipo as tipoconsumo',
                DB::raw('SUM(detalle_consumos_basicos.consumo) as total_consumo')
            )
            ->groupBy('tipoconsumo')
            ->orderBy('tipoconsumo')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Detalle tabla
        |--------------------------------------------------------------------------
        */
        $dtesConDetalles = DB::table('dtes')
            ->join('detalle_consumos_basicos', 'dtes.id', '=', 'detalle_consumos_basicos.dtes_id')
            ->join('clientesmedidores', 'detalle_consumos_basicos.numerocliente', '=', 'clientesmedidores.numerocliente')
            ->join('ccostos', 'clientesmedidores.ccosto', '=', 'ccostos.ccosto')
            ->join('cfinancieros', 'ccostos.cfinanciero', '=', 'cfinancieros.cfinanciero')
            ->where('dtes.RutEmisor', $rutEmisor)
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

        return view('dashboard.DashElectricidad', compact(
            'dtes',
            'meses',
            'cantidadDtes',
            'totalMontos',
            'documentosPorTipo',
            'consumoxCF',
            'consumoPorTipoConsumo',
            'consumoPorCentroCosto',
            'chartCentroCostoLabels',
            'chartCentroCostoSeries',
            'chartCentroCostoMontos',
            'dtesConDetalles',
            'graficoConsumoPeriodo',
            'chartPeriodoLabels',
            'chartMontoMensual',
            'chartConsumoKw',
            'metricasDashboard'
        ));
    }

    public function dashAlertas_2()
    {
        $rutEmisor = '88272600-2';

        $promedioConsumoEnergetico = DB::table('detalle_consumos_basicos as det')
            ->join('dtes as d', 'det.dtes_id', '=', 'd.id')
            ->where('d.RutEmisor', $rutEmisor)
            ->avg('det.consumo');

        $clientesFueraDeRango = DB::table('detalle_consumos_basicos as det')
            ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
            ->join('dtes as d', 'det.dtes_id', '=', 'd.id')
            ->where('d.RutEmisor', $rutEmisor)
            ->select(
                'cm.numerocliente',
                'cm.tipo',
                DB::raw('SUM(det.consumo) as total_consumo'),
                DB::raw("DATE_FORMAT(d.FechaRecepcionSII, '%Y-%m') as mes")
            )
            ->groupBy('cm.numerocliente', 'cm.tipo', 'mes')
            ->havingRaw('SUM(det.consumo) > ? OR SUM(det.consumo) < ?', [
                $promedioConsumoEnergetico * 1.15,
                $promedioConsumoEnergetico * 0.85
            ])
            ->orderBy('mes')
            ->get();

        $cantidadTotalDtes = Dtes::where('RutEmisor', $rutEmisor)->count();

        $cantidadDtesConDetalle = DB::table('detalle_consumos_basicos as det')
            ->join('dtes as d', 'det.dtes_id', '=', 'd.id')
            ->where('d.RutEmisor', $rutEmisor)
            ->distinct('det.dtes_id')
            ->count('det.dtes_id');

        $cantidadDtesSinDetalle = $cantidadTotalDtes - $cantidadDtesConDetalle;

        $resumenDtesConYsinDetalle = [
            'con_detalle' => $cantidadDtesConDetalle,
            'sin_detalle' => max($cantidadDtesSinDetalle, 0),
        ];

        return view('dashboard.alertas_2', compact(
            'clientesFueraDeRango',
            'promedioConsumoEnergetico',
            'resumenDtesConYsinDetalle'
        ));
    }
}