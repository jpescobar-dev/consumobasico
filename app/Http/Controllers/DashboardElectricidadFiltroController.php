<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dtes;

class DashboardElectricidadFiltroController extends Controller
{
    public function index(Request $request)
    {
        $rutEmisor = '88272600-2';
        $periodo = $request->get('periodo');
        $ccosto = $request->get('ccosto');

        $periodoExpr = "TRIM(COALESCE(NULLIF(d.Periodo, ''), DATE_FORMAT(d.FechaRecepcionSII, '%m/%Y')))";
        $anioExpr = "CAST(SUBSTRING_INDEX({$periodoExpr}, '/', -1) AS UNSIGNED)";
        $mesExpr = "CAST(SUBSTRING_INDEX({$periodoExpr}, '/', 1) AS UNSIGNED)";

        $dtesConDetalleSub = DB::table('detalle_consumos_basicos as det')
            ->select('det.dtes_id')
            ->distinct();

        $periodosDisponiblesQuery = DB::table('dtes as d')
            ->joinSub($dtesConDetalleSub, 'dd', function ($join) {
                $join->on('d.id', '=', 'dd.dtes_id');
            })
            ->where('d.RutEmisor', $rutEmisor);

        if ($ccosto) {
            $periodosDisponiblesQuery->whereExists(function ($q) use ($ccosto) {
                $q->select(DB::raw(1))
                    ->from('detalle_consumos_basicos as det')
                    ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
                    ->join('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
                    ->whereColumn('det.dtes_id', 'd.id')
                    ->where('cc.ccosto', $ccosto);
            });
        }

        $periodosDisponibles = $periodosDisponiblesQuery
            ->selectRaw("{$anioExpr} as periodo")
            ->groupByRaw($anioExpr)
            ->orderByDesc('periodo')
            ->pluck('periodo');

        $centrosCostosDisponiblesQuery = DB::table('dtes as d')
            ->join('detalle_consumos_basicos as det', 'd.id', '=', 'det.dtes_id')
            ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
            ->join('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
            ->where('d.RutEmisor', $rutEmisor);

        if ($periodo) {
            $centrosCostosDisponiblesQuery->whereRaw("{$anioExpr} = ?", [$periodo]);
        }

        $centrosCostosDisponibles = $centrosCostosDisponiblesQuery
            ->select('cc.ccosto', 'cc.nombre')
            ->distinct()
            ->orderBy('cc.nombre')
            ->get();

        $baseDtesFiltrados = DB::table('dtes as d')
            ->joinSub($dtesConDetalleSub, 'dd', function ($join) {
                $join->on('d.id', '=', 'dd.dtes_id');
            })
            ->where('d.RutEmisor', $rutEmisor);

        if ($periodo) {
            $baseDtesFiltrados->whereRaw("{$anioExpr} = ?", [$periodo]);
        }

        if ($ccosto) {
            $baseDtesFiltrados->whereExists(function ($q) use ($ccosto) {
                $q->select(DB::raw(1))
                    ->from('detalle_consumos_basicos as det')
                    ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
                    ->join('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
                    ->whereColumn('det.dtes_id', 'd.id')
                    ->where('cc.ccosto', $ccosto);
            });
        }

        $dtesIdsFiltradosSub = (clone $baseDtesFiltrados)->select('d.id');

        $faeSinDetalleBase = DB::table('dtes as d')
            ->where('d.RutEmisor', $rutEmisor)
            ->where('d.TipoDcto', 'FAE')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('detalle_consumos_basicos as det')
                    ->whereColumn('det.dtes_id', 'd.id');
            });

        if ($periodo) {
            $faeSinDetalleBase->whereRaw("{$anioExpr} = ?", [$periodo]);
        }

        $cantidadFaeSinDetalle = (clone $faeSinDetalleBase)->count();

        $urlFaeSinDetalle = route('dashboard.electricidad.fae_sin_detalle', array_filter([
            'periodo' => $periodo,
        ]));

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
            ->groupBy('det.dtes_id', 'cf.cfinanciero', 'cf.nombre');

        $asignacionCfBase = DB::table('dtes as d')
            ->joinSub($consumoPorCfYDte, 'pcf', function ($join) {
                $join->on('d.id', '=', 'pcf.dtes_id');
            })
            ->leftJoinSub($consumoTotalPorDte, 'ptd', function ($join) {
                $join->on('d.id', '=', 'ptd.dtes_id');
            })
            ->where('d.RutEmisor', $rutEmisor)
            ->selectRaw("
                d.id as dte_id,
                {$periodoExpr} as periodo_consumo,
                d.FechaRecepcionSII as fecha_orden,
                pcf.cfinanciero,
                pcf.nombre_cfinanciero,
                pcf.consumo_cf_dte as consumo_kw,
                CASE
                    WHEN COALESCE(ptd.total_consumo_dte, 0) > 0
                    THEN d.Monto * (pcf.consumo_cf_dte / ptd.total_consumo_dte)
                    ELSE 0
                END as monto_prorrateado
            ");

        if ($periodo) {
            $asignacionCfBase->whereRaw("{$anioExpr} = ?", [$periodo]);
        }

        $asignacionCf = DB::query()->fromSub($asignacionCfBase, 'acf');

        $consumoPorCcostoYDte = DB::table('detalle_consumos_basicos as det')
            ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
            ->join('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
            ->join('cfinancieros as cf', 'cc.cfinanciero', '=', 'cf.cfinanciero')
            ->select(
                'det.dtes_id',
                'cc.ccosto',
                'cc.nombre as nombre_ccosto',
                'cf.cfinanciero',
                'cf.nombre as nombre_cfinanciero',
                DB::raw('SUM(det.consumo) as consumo_ccosto_dte')
            )
            ->groupBy(
                'det.dtes_id',
                'cc.ccosto',
                'cc.nombre',
                'cf.cfinanciero',
                'cf.nombre'
            );

        $asignacionCcBase = DB::table('dtes as d')
            ->joinSub($consumoPorCcostoYDte, 'pcc', function ($join) {
                $join->on('d.id', '=', 'pcc.dtes_id');
            })
            ->leftJoinSub($consumoTotalPorDte, 'ptd', function ($join) {
                $join->on('d.id', '=', 'ptd.dtes_id');
            })
            ->where('d.RutEmisor', $rutEmisor)
            ->selectRaw("
                d.id as dte_id,
                {$periodoExpr} as periodo_consumo,
                d.FechaRecepcionSII as fecha_orden,
                pcc.ccosto,
                pcc.nombre_ccosto,
                pcc.cfinanciero,
                pcc.nombre_cfinanciero,
                pcc.consumo_ccosto_dte as consumo_kw,
                CASE
                    WHEN COALESCE(ptd.total_consumo_dte, 0) > 0
                    THEN d.Monto * (pcc.consumo_ccosto_dte / ptd.total_consumo_dte)
                    ELSE 0
                END as monto_prorrateado
            ");

        if ($periodo) {
            $asignacionCcBase->whereRaw("{$anioExpr} = ?", [$periodo]);
        }

        if ($ccosto) {
            $asignacionCcBase->where('pcc.ccosto', $ccosto);
        }

        $asignacionCc = DB::query()->fromSub($asignacionCcBase, 'acc');

        if ($ccosto) {
            $graficoConsumoPeriodo = (clone $asignacionCc)
                ->select(
                    'acc.periodo_consumo',
                    DB::raw('MIN(acc.fecha_orden) as fecha_orden'),
                    DB::raw('COUNT(DISTINCT acc.dte_id) as cantidad_documentos'),
                    DB::raw('SUM(acc.monto_prorrateado) as total_pesos'),
                    DB::raw('SUM(acc.consumo_kw) as total_kw')
                )
                ->groupBy('acc.periodo_consumo')
                ->orderBy('fecha_orden')
                ->get();
        } else {
            $graficoQuery = DB::table('dtes as d')
                ->joinSub($dtesConDetalleSub, 'dd', function ($join) {
                    $join->on('d.id', '=', 'dd.dtes_id');
                })
                ->leftJoinSub($consumoPorDte, 'cd', function ($join) {
                    $join->on('d.id', '=', 'cd.dtes_id');
                })
                ->where('d.RutEmisor', $rutEmisor);

            if ($periodo) {
                $graficoQuery->whereRaw("{$anioExpr} = ?", [$periodo]);
            }

            $graficoConsumoPeriodo = $graficoQuery
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
        }

        $chartPeriodoLabels = $graficoConsumoPeriodo->pluck('periodo_consumo')->values();
        $chartMontoMensual = $graficoConsumoPeriodo->pluck('total_pesos')->map(fn ($v) => (float) $v)->values();
        $chartConsumoKw = $graficoConsumoPeriodo->pluck('total_kw')->map(fn ($v) => (float) $v)->values();

        if ($ccosto) {
            $totalDtes = (clone $asignacionCc)->distinct()->count('acc.dte_id');
            $totalMontoDashboard = (clone $asignacionCc)->sum('acc.monto_prorrateado');
            $totalConsumoDashboard = (clone $asignacionCc)->sum('acc.consumo_kw');
        } else {
            $totalDtes = (clone $baseDtesFiltrados)->count('d.id');
            $totalMontoDashboard = (clone $baseDtesFiltrados)->sum('d.Monto');

            $totalConsumoDashboard = DB::table('detalle_consumos_basicos as det')
                ->whereIn('det.dtes_id', clone $dtesIdsFiltradosSub)
                ->sum('det.consumo');
        }

        $metricasDashboard = [
            'total_dtes'             => (int) $totalDtes,
            'total_monto'            => (float) $totalMontoDashboard,
            'total_consumo_kw'       => (float) $totalConsumoDashboard,
            'promedio_monto_por_dte' => $totalDtes > 0 ? round($totalMontoDashboard / $totalDtes, 2) : 0,
            'cantidad_periodos'      => $graficoConsumoPeriodo->count(),
        ];

        $documentosPorTipo = DB::table('dtes as d')
            ->whereIn('d.id', clone $dtesIdsFiltradosSub)
            ->select('d.TipoDcto', DB::raw('COUNT(*) as total'))
            ->groupBy('d.TipoDcto')
            ->orderBy('d.TipoDcto')
            ->get();

        $detallePeriodoExpr = "TRIM(COALESCE(NULLIF(d.Periodo, ''), DATE_FORMAT(d.FechaRecepcionSII, '%m/%Y')))";
        $detalleMesExpr = "CAST(SUBSTRING_INDEX({$detallePeriodoExpr}, '/', 1) AS UNSIGNED)";

        $detalleFiltradoBase = DB::table('detalle_consumos_basicos as det')
            ->join('dtes as d', 'det.dtes_id', '=', 'd.id')
            ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
            ->leftJoin('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
            ->leftJoin('cfinancieros as cf', 'cc.cfinanciero', '=', 'cf.cfinanciero')
            ->where('d.RutEmisor', $rutEmisor)
            ->whereIn('d.id', clone $dtesIdsFiltradosSub);

        if ($ccosto) {
            $detalleFiltradoBase->where('cc.ccosto', $ccosto);
        }

        $consumoPorTipoConsumo = (clone $detalleFiltradoBase)
            ->select('cm.tipo as tipoconsumo', DB::raw('SUM(det.consumo) as total_consumo'))
            ->groupBy('cm.tipo')
            ->orderBy('cm.tipo')
            ->get();

        $tipoConsumoMensualExpr = "
            CASE
                WHEN LOWER(TRIM(cm.tipo)) IN ('calefaccion', 'calefacción') THEN 'Calefacción'
                WHEN LOWER(TRIM(cm.tipo)) = 'normal' THEN 'Normal'
                ELSE 'Otros'
            END
        ";

        $consumoMensualPorTipo = (clone $detalleFiltradoBase)
            ->selectRaw("
                {$detalleMesExpr} as mes_numero,
                {$tipoConsumoMensualExpr} as tipo_consumo,
                SUM(det.consumo) as total_consumo
            ")
            ->groupByRaw("{$detalleMesExpr}, {$tipoConsumoMensualExpr}")
            ->orderBy('mes_numero')
            ->get();

        $chartConsumoMensualLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $chartConsumoMensualNormal = array_fill(0, 12, 0);
        $chartConsumoMensualCalefaccion = array_fill(0, 12, 0);

        foreach ($consumoMensualPorTipo as $fila) {
            $indice = ((int) $fila->mes_numero) - 1;

            if ($indice < 0 || $indice > 11) {
                continue;
            }

            if ($fila->tipo_consumo === 'Normal') {
                $chartConsumoMensualNormal[$indice] = (float) $fila->total_consumo;
            }

            if ($fila->tipo_consumo === 'Calefacción') {
                $chartConsumoMensualCalefaccion[$indice] = (float) $fila->total_consumo;
            }
        }

        $consumoTipoPorDte = (clone $detalleFiltradoBase)
            ->selectRaw("
                d.id as dte_id,
                {$detalleMesExpr} as mes_numero,
                {$tipoConsumoMensualExpr} as tipo_consumo,
                SUM(det.consumo) as consumo_tipo_dte
            ")
            ->groupByRaw("d.id, {$detalleMesExpr}, {$tipoConsumoMensualExpr}");

        $consumoTotalPorDteFiltrado = (clone $detalleFiltradoBase)
            ->selectRaw("
                d.id as dte_id,
                SUM(det.consumo) as consumo_total_dte
            ")
            ->groupBy('d.id');

        $montoMensualPorTipo = DB::query()
            ->fromSub($consumoTipoPorDte, 'ctd')
            ->joinSub($consumoTotalPorDteFiltrado, 'ttd', function ($join) {
                $join->on('ctd.dte_id', '=', 'ttd.dte_id');
            })
            ->join('dtes as d2', 'd2.id', '=', 'ctd.dte_id')
            ->selectRaw("
                ctd.mes_numero,
                ctd.tipo_consumo,
                SUM(
                    CASE
                        WHEN COALESCE(ttd.consumo_total_dte, 0) > 0
                        THEN d2.Monto * (ctd.consumo_tipo_dte / ttd.consumo_total_dte)
                        ELSE 0
                    END
                ) as total_monto
            ")
            ->groupBy('ctd.mes_numero', 'ctd.tipo_consumo')
            ->orderBy('ctd.mes_numero')
            ->get();

        $chartMontoMensualNormal = array_fill(0, 12, 0);
        $chartMontoMensualCalefaccion = array_fill(0, 12, 0);

        foreach ($montoMensualPorTipo as $fila) {
            $indice = ((int) $fila->mes_numero) - 1;

            if ($indice < 0 || $indice > 11) {
                continue;
            }

            if ($fila->tipo_consumo === 'Normal') {
                $chartMontoMensualNormal[$indice] = (float) $fila->total_monto;
            }

            if ($fila->tipo_consumo === 'Calefacción') {
                $chartMontoMensualCalefaccion[$indice] = (float) $fila->total_monto;
            }
        }

        $consumoxCF = (clone $asignacionCc)
            ->select(
                'acc.cfinanciero',
                'acc.nombre_cfinanciero',
                DB::raw('SUM(acc.monto_prorrateado) as total_monto'),
                DB::raw('SUM(acc.consumo_kw) as total_consumo'),
                DB::raw('COUNT(DISTINCT acc.dte_id) as cantidad_documentos')
            )
            ->groupBy('acc.cfinanciero', 'acc.nombre_cfinanciero')
            ->orderBy('acc.nombre_cfinanciero')
            ->get();

        $topCentroCosto = 10;

        $consumoPorCentroCosto = (clone $asignacionCc)
            ->select(
                'acc.ccosto',
                'acc.nombre_ccosto',
                DB::raw('SUM(acc.consumo_kw) as total_consumo'),
                DB::raw('SUM(acc.monto_prorrateado) as total_monto'),
                DB::raw('COUNT(DISTINCT acc.dte_id) as cantidad_documentos')
            )
            ->groupBy('acc.ccosto', 'acc.nombre_ccosto')
            ->orderByDesc(DB::raw('SUM(acc.consumo_kw)'))
            ->limit($topCentroCosto)
            ->get();

        $tipoConsumoCentroCostoExpr = "
            CASE
                WHEN LOWER(TRIM(cm.tipo)) IN ('calefaccion', 'calefacción') THEN 'Calefacción'
                WHEN LOWER(TRIM(cm.tipo)) = 'normal' THEN 'Normal'
                ELSE 'Otros'
            END
        ";

        $consumoCentroCostoPorTipo = (clone $detalleFiltradoBase)
            ->selectRaw("
                cc.ccosto,
                cc.nombre as nombre_ccosto,
                {$tipoConsumoCentroCostoExpr} as tipo_consumo,
                SUM(det.consumo) as total_consumo
            ")
            ->whereNotNull('cc.ccosto')
            ->groupBy('cc.ccosto', 'cc.nombre')
            ->groupByRaw($tipoConsumoCentroCostoExpr)
            ->orderBy('cc.nombre')
            ->get();

        $chartCentroCostoKeys = $consumoPorCentroCosto->pluck('ccosto')->values();
        $chartCentroCostoLabels = $consumoPorCentroCosto->pluck('nombre_ccosto')->values();
        $chartCentroCostoMontos = $consumoPorCentroCosto->pluck('total_monto')->map(fn ($v) => (float) $v)->values();

        $chartCentroCostoNormal = array_fill(0, $chartCentroCostoLabels->count(), 0);
        $chartCentroCostoCalefaccion = array_fill(0, $chartCentroCostoLabels->count(), 0);
        $chartCentroCostoOtros = array_fill(0, $chartCentroCostoLabels->count(), 0);

        $indiceCentroCosto = [];
        foreach ($chartCentroCostoKeys as $i => $key) {
            $indiceCentroCosto[(string) $key] = $i;
        }

        foreach ($consumoCentroCostoPorTipo as $fila) {
            $key = (string) $fila->ccosto;

            if (!array_key_exists($key, $indiceCentroCosto)) {
                continue;
            }

            $idx = $indiceCentroCosto[$key];
            $valor = (float) $fila->total_consumo;

            if ($fila->tipo_consumo === 'Normal') {
                $chartCentroCostoNormal[$idx] = $valor;
            } elseif ($fila->tipo_consumo === 'Calefacción') {
                $chartCentroCostoCalefaccion[$idx] = $valor;
            } else {
                $chartCentroCostoOtros[$idx] = $valor;
            }
        }

        $consumoCentroCostoTipoPorDte = (clone $detalleFiltradoBase)
            ->selectRaw("
                d.id as dte_id,
                cc.ccosto,
                cc.nombre as nombre_ccosto,
                {$tipoConsumoCentroCostoExpr} as tipo_consumo,
                SUM(det.consumo) as consumo_tipo_ccosto_dte
            ")
            ->whereNotNull('cc.ccosto')
            ->groupByRaw("d.id, cc.ccosto, cc.nombre, {$tipoConsumoCentroCostoExpr}");

        $montoCentroCostoPorTipo = DB::query()
            ->fromSub($consumoCentroCostoTipoPorDte, 'cct')
            ->leftJoinSub($consumoTotalPorDte, 'ptd', function ($join) {
                $join->on('cct.dte_id', '=', 'ptd.dtes_id');
            })
            ->join('dtes as d2', 'd2.id', '=', 'cct.dte_id')
            ->selectRaw("
                cct.ccosto,
                cct.nombre_ccosto,
                cct.tipo_consumo,
                SUM(
                    CASE
                        WHEN COALESCE(ptd.total_consumo_dte, 0) > 0
                        THEN d2.Monto * (cct.consumo_tipo_ccosto_dte / ptd.total_consumo_dte)
                        ELSE 0
                    END
                ) as total_monto
            ")
            ->groupBy('cct.ccosto', 'cct.nombre_ccosto', 'cct.tipo_consumo')
            ->orderBy('cct.nombre_ccosto')
            ->get();

        $chartCentroCostoMontoNormal = array_fill(0, $chartCentroCostoLabels->count(), 0);
        $chartCentroCostoMontoCalefaccion = array_fill(0, $chartCentroCostoLabels->count(), 0);
        $chartCentroCostoMontoOtros = array_fill(0, $chartCentroCostoLabels->count(), 0);

        foreach ($montoCentroCostoPorTipo as $fila) {
            $key = (string) $fila->ccosto;

            if (!array_key_exists($key, $indiceCentroCosto)) {
                continue;
            }

            $idx = $indiceCentroCosto[$key];
            $valor = (float) $fila->total_monto;

            if ($fila->tipo_consumo === 'Normal') {
                $chartCentroCostoMontoNormal[$idx] = $valor;
            } elseif ($fila->tipo_consumo === 'Calefacción') {
                $chartCentroCostoMontoCalefaccion[$idx] = $valor;
            } else {
                $chartCentroCostoMontoOtros[$idx] = $valor;
            }
        }

        $dtesConDetalles = DB::table('dtes as d')
            ->join('detalle_consumos_basicos as det', 'd.id', '=', 'det.dtes_id')
            ->join('clientesmedidores as cm', 'det.numerocliente', '=', 'cm.numerocliente')
            ->leftJoin('ccostos as cc', 'cm.ccosto', '=', 'cc.ccosto')
            ->leftJoin('cfinancieros as cf', 'cc.cfinanciero', '=', 'cf.cfinanciero')
            ->where('d.RutEmisor', $rutEmisor)
            ->whereIn('d.id', clone $dtesIdsFiltradosSub);

        if ($ccosto) {
            $dtesConDetalles->where('cc.ccosto', $ccosto);
        }

        $dtesConDetalles = $dtesConDetalles
            ->select(
                'd.id as dte_id',
                'd.NumeroDte',
                'd.Fecha',
                'd.Periodo',
                'd.Monto',
                'd.RutEmisor',
                'det.tipo',
                'det.consumo',
                'det.numerocliente',
                'cm.ccosto',
                'cm.tipo as tipoconsumo',
                'cc.nombre as nombre_ccosto',
                'cf.cfinanciero',
                'cf.nombre as nombre_cfinanciero'
            )
            ->orderBy('d.Fecha', 'desc')
            ->get();

        return view('dashboard.DashElectricidadFiltro', compact(
            'periodo',
            'ccosto',
            'periodosDisponibles',
            'centrosCostosDisponibles',
            'documentosPorTipo',
            'consumoxCF',
            'consumoPorTipoConsumo',
            'consumoPorCentroCosto',
            'chartCentroCostoLabels',
            'chartCentroCostoNormal',
            'chartCentroCostoCalefaccion',
            'chartCentroCostoOtros',
            'chartCentroCostoMontos',
            'chartCentroCostoMontoNormal',
            'chartCentroCostoMontoCalefaccion',
            'chartCentroCostoMontoOtros',
            'dtesConDetalles',
            'graficoConsumoPeriodo',
            'chartPeriodoLabels',
            'chartMontoMensual',
            'chartConsumoKw',
            'chartConsumoMensualLabels',
            'chartConsumoMensualNormal',
            'chartConsumoMensualCalefaccion',
            'chartMontoMensualNormal',
            'chartMontoMensualCalefaccion',
            'metricasDashboard',
            'cantidadFaeSinDetalle',
            'urlFaeSinDetalle'
        ));
    }

    public function faeSinDetalle(Request $request)
    {
        $rutEmisor = '88272600-2';
        $periodo = $request->get('periodo');

        $periodoExpr = "TRIM(COALESCE(NULLIF(d.Periodo, ''), DATE_FORMAT(d.FechaRecepcionSII, '%m/%Y')))";
        $anioExpr = "CAST(SUBSTRING_INDEX({$periodoExpr}, '/', -1) AS UNSIGNED)";

        $faeSinDetalleQuery = DB::table('dtes as d')
            ->where('d.RutEmisor', $rutEmisor)
            ->where('d.TipoDcto', 'FAE')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('detalle_consumos_basicos as det')
                    ->whereColumn('det.dtes_id', 'd.id');
            });

        if ($periodo) {
            $faeSinDetalleQuery->whereRaw("{$anioExpr} = ?", [$periodo]);
        }

        $faeSinDetalle = $faeSinDetalleQuery
            ->selectRaw("
                d.id,
                d.NumeroDte,
                d.Fecha,
                {$periodoExpr} as periodo_consumo,
                d.Monto,
                d.RutEmisor,
                d.TipoDcto
            ")
            ->orderBy('d.Fecha', 'desc')
            ->get();

        $cantidadFaeSinDetalle = $faeSinDetalle->count();

        return view('dashboard.FaeSinDetalle', compact(
            'faeSinDetalle',
            'cantidadFaeSinDetalle',
            'periodo'
        ));
    }
}
