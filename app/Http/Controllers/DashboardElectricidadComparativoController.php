<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardElectricidadComparativoController extends Controller
{
    public function index(Request $request)
    {
        $rutEmisor = '88272600-2';
        $cfinanciero = $request->get('cfinanciero');
        $tipoConsumo = $request->get('tipo_consumo');

        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        $tiposConsumoDisponibles = [
            'Normal' => 'Normal',
            'Calefacción' => 'Calefacción',
        ];

        $periodoExpr = "TRIM(COALESCE(NULLIF(d.Periodo, ''), DATE_FORMAT(d.FechaRecepcionSII, '%m/%Y')))";
        $anioExpr = "CAST(SUBSTRING_INDEX({$periodoExpr}, '/', -1) AS UNSIGNED)";
        $mesExpr = "CAST(SUBSTRING_INDEX({$periodoExpr}, '/', 1) AS UNSIGNED)";

        $tipoConsumoExpr = "
            CASE
                WHEN LOWER(TRIM(cm.tipo)) IN ('calefaccion', 'calefacción') THEN 'Calefacción'
                WHEN LOWER(TRIM(cm.tipo)) = 'normal' THEN 'Normal'
                ELSE 'Otros'
            END
        ";

        $consumoTotalPorDte = DB::table('detalle_consumos_basicos as det')
            ->select(
                'det.dtes_id',
                DB::raw('SUM(det.consumo) as total_consumo_dte')
            )
            ->groupBy('det.dtes_id');

        $aniosDisponiblesQuery = DB::table('detalle_consumos_basicos as det')
            ->join('dtes as d', 'det.dtes_id', '=', 'd.id')
            ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
            ->leftJoin('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
            ->leftJoin('cfinancieros as cf', 'cc.cfinanciero', '=', 'cf.cfinanciero')
            ->where('d.RutEmisor', $rutEmisor)
            ->whereRaw("{$anioExpr} IS NOT NULL")
            ->whereRaw("{$mesExpr} BETWEEN 1 AND 12");

        if ($cfinanciero) {
            $aniosDisponiblesQuery->where('cf.cfinanciero', $cfinanciero);
        }

        if ($tipoConsumo) {
            $aniosDisponiblesQuery->whereRaw("{$tipoConsumoExpr} = ?", [$tipoConsumo]);
        }

        $aniosDisponibles = $aniosDisponiblesQuery
            ->selectRaw("DISTINCT {$anioExpr} as anio")
            ->orderByDesc('anio')
            ->pluck('anio')
            ->map(fn ($v) => (int) $v)
            ->values();

        $anio = (int) ($request->get('anio') ?: ($aniosDisponibles->first() ?? date('Y')));
        $mes = (int) ($request->get('mes') ?: date('n'));
        $anioAnterior = $anio - 1;

        $centrosFinancierosDisponiblesQuery = DB::table('detalle_consumos_basicos as det')
            ->join('dtes as d', 'det.dtes_id', '=', 'd.id')
            ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
            ->join('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
            ->join('cfinancieros as cf', 'cc.cfinanciero', '=', 'cf.cfinanciero')
            ->where('d.RutEmisor', $rutEmisor);

        if ($tipoConsumo) {
            $centrosFinancierosDisponiblesQuery->whereRaw("{$tipoConsumoExpr} = ?", [$tipoConsumo]);
        }

        $centrosFinancierosDisponibles = $centrosFinancierosDisponiblesQuery
            ->select('cf.cfinanciero', 'cf.nombre')
            ->distinct()
            ->orderBy('cf.nombre')
            ->get();

        $consumoFiltradoPorDteMes = DB::table('detalle_consumos_basicos as det')
            ->join('dtes as d', 'det.dtes_id', '=', 'd.id')
            ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
            ->leftJoin('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
            ->leftJoin('cfinancieros as cf', 'cc.cfinanciero', '=', 'cf.cfinanciero')
            ->where('d.RutEmisor', $rutEmisor)
            ->whereRaw("{$anioExpr} IS NOT NULL")
            ->whereRaw("{$mesExpr} BETWEEN 1 AND 12");

        if ($cfinanciero) {
            $consumoFiltradoPorDteMes->where('cf.cfinanciero', $cfinanciero);
        }

        if ($tipoConsumo) {
            $consumoFiltradoPorDteMes->whereRaw("{$tipoConsumoExpr} = ?", [$tipoConsumo]);
        }

        $consumoFiltradoPorDteMes = $consumoFiltradoPorDteMes
            ->selectRaw("
                d.id as dte_id,
                {$anioExpr} as anio,
                {$mesExpr} as mes,
                SUM(det.consumo) as consumo_filtrado_dte
            ")
            ->groupByRaw("d.id, {$anioExpr}, {$mesExpr}");

        $monthlyAggregates = DB::query()
            ->fromSub($consumoFiltradoPorDteMes, 'cfd')
            ->leftJoinSub($consumoTotalPorDte, 'ptd', function ($join) {
                $join->on('cfd.dte_id', '=', 'ptd.dtes_id');
            })
            ->join('dtes as d2', 'd2.id', '=', 'cfd.dte_id')
            ->selectRaw("
                cfd.anio,
                cfd.mes,
                SUM(
                    CASE
                        WHEN COALESCE(ptd.total_consumo_dte, 0) > 0
                        THEN d2.Monto * (cfd.consumo_filtrado_dte / ptd.total_consumo_dte)
                        ELSE 0
                    END
                ) as total_pesos,
                SUM(cfd.consumo_filtrado_dte) as total_kw
            ")
            ->groupBy('cfd.anio', 'cfd.mes')
            ->orderBy('cfd.anio')
            ->orderBy('cfd.mes')
            ->get();

        $monthlyIndex = $monthlyAggregates->keyBy(function ($row) {
            return $row->anio . '-' . $row->mes;
        });

        $registroActual = $monthlyIndex->get($anio . '-' . $mes);
        $registroAnterior = $monthlyIndex->get($anioAnterior . '-' . $mes);

        $montoActual = (float) ($registroActual->total_pesos ?? 0);
        $kwActual = (float) ($registroActual->total_kw ?? 0);
        $montoAnterior = (float) ($registroAnterior->total_pesos ?? 0);
        $kwAnterior = (float) ($registroAnterior->total_kw ?? 0);

        $calcularVariacion = function ($actual, $anterior) {
            if ((float) $anterior <= 0) {
                return null;
            }

            return round((($actual - $anterior) / $anterior) * 100, 2);
        };

        $metricasComparativas = [
            'monto_actual' => $montoActual,
            'kw_actual' => $kwActual,
            'monto_anterior' => $montoAnterior,
            'kw_anterior' => $kwAnterior,
            'variacion_monto_pct' => $calcularVariacion($montoActual, $montoAnterior),
            'variacion_kw_pct' => $calcularVariacion($kwActual, $kwAnterior),
        ];

        $comparacionMesAnios = $aniosDisponibles
            ->sort()
            ->values()
            ->map(function ($anioItem) use ($mes, $monthlyIndex, $meses, $calcularVariacion) {
                $actual = $monthlyIndex->get($anioItem . '-' . $mes);
                $anterior = $monthlyIndex->get(($anioItem - 1) . '-' . $mes);

                $monto = (float) ($actual->total_pesos ?? 0);
                $kw = (float) ($actual->total_kw ?? 0);
                $montoPrevio = (float) ($anterior->total_pesos ?? 0);
                $kwPrevio = (float) ($anterior->total_kw ?? 0);

                return [
                    'anio' => (int) $anioItem,
                    'mes_numero' => $mes,
                    'mes_nombre' => $meses[$mes],
                    'monto' => $monto,
                    'kw' => $kw,
                    'variacion_monto_pct' => $calcularVariacion($monto, $montoPrevio),
                    'variacion_kw_pct' => $calcularVariacion($kw, $kwPrevio),
                ];
            })
            ->values();

        $tablaComparativaMes = $comparacionMesAnios->sortByDesc('anio')->values();

        $chartComparativoAniosLabels = $comparacionMesAnios->pluck('anio')->values();
        $chartComparativoAniosMontos = $comparacionMesAnios->pluck('monto')->map(fn ($v) => (float) $v)->values();
        $chartComparativoAniosKw = $comparacionMesAnios->pluck('kw')->map(fn ($v) => (float) $v)->values();

        $chartEvolucionMensualLabels = collect($meses)->values();

        $chartEvolucionMontoActual = [];
        $chartEvolucionMontoAnterior = [];
        $chartEvolucionKwActual = [];
        $chartEvolucionKwAnterior = [];

        for ($i = 1; $i <= 12; $i++) {
            $filaActual = $monthlyIndex->get($anio . '-' . $i);
            $filaAnterior = $monthlyIndex->get($anioAnterior . '-' . $i);

            $chartEvolucionMontoActual[] = (float) ($filaActual->total_pesos ?? 0);
            $chartEvolucionMontoAnterior[] = (float) ($filaAnterior->total_pesos ?? 0);
            $chartEvolucionKwActual[] = (float) ($filaActual->total_kw ?? 0);
            $chartEvolucionKwAnterior[] = (float) ($filaAnterior->total_kw ?? 0);
        }

        $consumoCentroCostoPorDte = DB::table('detalle_consumos_basicos as det')
            ->join('dtes as d', 'det.dtes_id', '=', 'd.id')
            ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
            ->leftJoin('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
            ->leftJoin('cfinancieros as cf', 'cc.cfinanciero', '=', 'cf.cfinanciero')
            ->where('d.RutEmisor', $rutEmisor)
            ->whereRaw("{$anioExpr} IN (?, ?)", [$anioAnterior, $anio])
            ->whereRaw("{$mesExpr} = ?", [$mes]);

        if ($cfinanciero) {
            $consumoCentroCostoPorDte->where('cf.cfinanciero', $cfinanciero);
        }

        if ($tipoConsumo) {
            $consumoCentroCostoPorDte->whereRaw("{$tipoConsumoExpr} = ?", [$tipoConsumo]);
        }

        $consumoCentroCostoPorDte = $consumoCentroCostoPorDte
            ->selectRaw("
                d.id as dte_id,
                {$anioExpr} as anio,
                cc.ccosto,
                cc.nombre as nombre_ccosto,
                SUM(det.consumo) as consumo_ccosto_dte
            ")
            ->whereNotNull('cc.ccosto')
            ->groupByRaw("d.id, {$anioExpr}, cc.ccosto, cc.nombre");

        $comparativoCentroCosto = DB::query()
            ->fromSub($consumoCentroCostoPorDte, 'ccd')
            ->leftJoinSub($consumoTotalPorDte, 'ptd', function ($join) {
                $join->on('ccd.dte_id', '=', 'ptd.dtes_id');
            })
            ->join('dtes as d2', 'd2.id', '=', 'ccd.dte_id')
            ->selectRaw("
                ccd.anio,
                ccd.ccosto,
                ccd.nombre_ccosto,
                SUM(
                    CASE
                        WHEN COALESCE(ptd.total_consumo_dte, 0) > 0
                        THEN d2.Monto * (ccd.consumo_ccosto_dte / ptd.total_consumo_dte)
                        ELSE 0
                    END
                ) as total_pesos,
                SUM(ccd.consumo_ccosto_dte) as total_kw
            ")
            ->groupBy('ccd.anio', 'ccd.ccosto', 'ccd.nombre_ccosto')
            ->get();

        $rankingCentroCosto = $comparativoCentroCosto
            ->groupBy('ccosto')
            ->map(function ($items) {
                $primero = $items->first();

                return [
                    'ccosto' => $primero->ccosto,
                    'nombre_ccosto' => $primero->nombre_ccosto,
                    'score' => $items->sum('total_pesos'),
                ];
            })
            ->sortByDesc('score')
            ->values();

        $chartCentroCostoComparativoLabels = $rankingCentroCosto
            ->pluck('nombre_ccosto')
            ->values();

        $chartCentroCostoMontoActual = [];
        $chartCentroCostoMontoAnterior = [];
        $chartCentroCostoKwActual = [];
        $chartCentroCostoKwAnterior = [];

        foreach ($rankingCentroCosto as $item) {
            $filaActual = $comparativoCentroCosto
                ->first(fn ($r) => (string) $r->ccosto === (string) $item['ccosto'] && (int) $r->anio === (int) $anio);

            $filaAnterior = $comparativoCentroCosto
                ->first(fn ($r) => (string) $r->ccosto === (string) $item['ccosto'] && (int) $r->anio === (int) $anioAnterior);

            $chartCentroCostoMontoActual[] = (float) ($filaActual->total_pesos ?? 0);
            $chartCentroCostoMontoAnterior[] = (float) ($filaAnterior->total_pesos ?? 0);
            $chartCentroCostoKwActual[] = (float) ($filaActual->total_kw ?? 0);
            $chartCentroCostoKwAnterior[] = (float) ($filaAnterior->total_kw ?? 0);
        }

        return view('dashboard.DashElectricidadComparativo', compact(
            'anio',
            'mes',
            'anioAnterior',
            'meses',
            'cfinanciero',
            'tipoConsumo',
            'tiposConsumoDisponibles',
            'aniosDisponibles',
            'centrosFinancierosDisponibles',
            'metricasComparativas',
            'tablaComparativaMes',
            'chartComparativoAniosLabels',
            'chartComparativoAniosMontos',
            'chartComparativoAniosKw',
            'chartEvolucionMensualLabels',
            'chartEvolucionMontoActual',
            'chartEvolucionMontoAnterior',
            'chartEvolucionKwActual',
            'chartEvolucionKwAnterior',
            'chartCentroCostoComparativoLabels',
            'chartCentroCostoMontoActual',
            'chartCentroCostoMontoAnterior',
            'chartCentroCostoKwActual',
            'chartCentroCostoKwAnterior'
        ));
    }
}