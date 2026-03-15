@extends('layouts.theme.app')

@section('title2', 'Electricidad')
@section('title', 'Dashboard Comparativo')

@section('styles')
    <style>
        .metric-card {
            border: 1px solid #e0e6ed;
            border-radius: 10px;
            background: #fff;
            padding: 18px;
            box-shadow: 0 0 20px rgba(94, 92, 154, 0.06);
            height: 100%;
        }

        .metric-card .metric-label {
            font-size: 0.85rem;
            color: #888ea8;
            margin-bottom: 8px;
        }

        .metric-card .metric-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #3b3f5c;
            margin: 0;
        }

        .metric-card .metric-meta {
            margin-top: 10px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .metric-up {
            color: #00ab55;
        }

        .metric-down {
            color: #e7515a;
        }

        .metric-neutral {
            color: #888ea8;
        }

        .chart-card {
            border: 1px solid #e0e6ed;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 0 20px rgba(94, 92, 154, 0.06);
            padding: 16px;
            height: 100%;
        }

        .chart-card h5 {
            margin-bottom: 15px;
            color: #3b3f5c;
        }

        .chart-wrapper {
            position: relative;
            height: 360px;
        }

        .filter-card {
            border: 1px solid #e0e6ed;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 0 20px rgba(94, 92, 154, 0.06);
            padding: 18px;
            margin-bottom: 20px;
        }

        .filter-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #3b3f5c;
            margin-bottom: 6px;
        }

        .table-card {
            border: 1px solid #e0e6ed;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 0 20px rgba(94, 92, 154, 0.06);
            padding: 16px;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('content')
<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    @include('layouts.theme.partials.breadcrumb')
                </div>

                <div>
                    <h4 class="mb-0">Dashboard Comparativo de Electricidad</h4>
                </div>

                <div>
                    <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm">Volver</a>
                </div>
            </div>

            <div class="filter-card">
                <form method="GET" action="{{ route('dashboard.electricidad.comparativo') }}">
                    <div class="row align-items-end">
                        <div class="col-md-2 mb-3">
                            <label class="filter-label">Año</label>
                            <select name="anio" class="form-control">
                                @foreach($aniosDisponibles as $item)
                                    <option value="{{ $item }}" @selected((int)$anio === (int)$item)>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="filter-label">Mes</label>
                            <select name="mes" class="form-control">
                                @foreach($meses as $numero => $nombre)
                                    <option value="{{ $numero }}" @selected((int)$mes === (int)$numero)>
                                        {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="filter-label">Centro Financiero</label>
                            <select name="cfinanciero" class="form-control">
                                <option value="">Todos los centros financieros</option>
                                @foreach($centrosFinancierosDisponibles as $cf)
                                    <option value="{{ $cf->cfinanciero }}" @selected($cfinanciero == $cf->cfinanciero)>
                                        {{ $cf->nombre }} ({{ $cf->cfinanciero }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="filter-label">Tipo consumo</label>
                            <select name="tipo_consumo" class="form-control">
                                <option value="">Todos</option>
                                @foreach($tiposConsumoDisponibles as $valor => $label)
                                    <option value="{{ $valor }}" @selected($tipoConsumo == $valor)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3 d-flex">
                            <button type="submit" class="btn btn-primary mr-2">Filtrar</button>
                            <a href="{{ route('dashboard.electricidad.comparativo') }}" class="btn btn-outline-secondary">Limpiar</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row layout-top-spacing">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="metric-card">
                        <div class="metric-label">Monto {{ $meses[$mes] }} {{ $anio }}</div>
                        <h3 class="metric-value">${{ number_format($metricasComparativas['monto_actual'] ?? 0, 0, ',', '.') }}</h3>
                        <div class="metric-meta metric-neutral">
                            Año anterior: ${{ number_format($metricasComparativas['monto_anterior'] ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="metric-card">
                        <div class="metric-label">Consumo kW {{ $meses[$mes] }} {{ $anio }}</div>
                        <h3 class="metric-value">{{ number_format($metricasComparativas['kw_actual'] ?? 0, 2, ',', '.') }}</h3>
                        <div class="metric-meta metric-neutral">
                            Año anterior: {{ number_format($metricasComparativas['kw_anterior'] ?? 0, 2, ',', '.') }} kW
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="metric-card">
                        <div class="metric-label">Variación $ vs {{ $anioAnterior }}</div>
                        <h3 class="metric-value">
                            @if(!is_null($metricasComparativas['variacion_monto_pct']))
                                {{ number_format($metricasComparativas['variacion_monto_pct'], 2, ',', '.') }}%
                            @else
                                N/D
                            @endif
                        </h3>
                        <div class="metric-meta {{ is_null($metricasComparativas['variacion_monto_pct']) ? 'metric-neutral' : (($metricasComparativas['variacion_monto_pct'] >= 0) ? 'metric-up' : 'metric-down') }}">
                            @if(!is_null($metricasComparativas['variacion_monto_pct']))
                                {{ $metricasComparativas['variacion_monto_pct'] >= 0 ? 'Incremento' : 'Disminución' }}
                            @else
                                Sin base de comparación
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="metric-card">
                        <div class="metric-label">Variación kW vs {{ $anioAnterior }}</div>
                        <h3 class="metric-value">
                            @if(!is_null($metricasComparativas['variacion_kw_pct']))
                                {{ number_format($metricasComparativas['variacion_kw_pct'], 2, ',', '.') }}%
                            @else
                                N/D
                            @endif
                        </h3>
                        <div class="metric-meta {{ is_null($metricasComparativas['variacion_kw_pct']) ? 'metric-neutral' : (($metricasComparativas['variacion_kw_pct'] >= 0) ? 'metric-up' : 'metric-down') }}">
                            @if(!is_null($metricasComparativas['variacion_kw_pct']))
                                {{ $metricasComparativas['variacion_kw_pct'] >= 0 ? 'Incremento' : 'Disminución' }}
                            @else
                                Sin base de comparación
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 mb-4">
                    <div class="chart-card">
                        <h5>Monto de {{ $meses[$mes] }} comparado entre años</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartComparativoMonto"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 mb-4">
                    <div class="chart-card">
                        <h5>Consumo kW de {{ $meses[$mes] }} comparado entre años</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartComparativoKw"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 mb-4">
                    <div class="chart-card">
                        <h5>Evolución mensual de monto: {{ $anio }} vs {{ $anioAnterior }}</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartEvolucionMonto"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 mb-4">
                    <div class="chart-card">
                        <h5>Evolución mensual de consumo kW: {{ $anio }} vs {{ $anioAnterior }}</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartEvolucionKw"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 mb-4">
                    <div class="chart-card">
                        <h5>Comparativo por centros de costos: {{ $meses[$mes] }} {{ $anio }} vs {{ $anioAnterior }}</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartCentroCostoComparativo"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <h5 class="mb-3">Resumen comparativo del mes {{ $meses[$mes] }}</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Año</th>
                                <th>Mes</th>
                                <th class="text-right">Monto</th>
                                <th class="text-right">kW</th>
                                <th class="text-right">Variación $ %</th>
                                <th class="text-right">Variación kW %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tablaComparativaMes as $fila)
                                <tr>
                                    <td>{{ $fila['anio'] }}</td>
                                    <td>{{ $fila['mes_nombre'] }}</td>
                                    <td class="text-right">${{ number_format($fila['monto'], 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($fila['kw'], 2, ',', '.') }}</td>
                                    <td class="text-right">
                                        @if(!is_null($fila['variacion_monto_pct']))
                                            {{ number_format($fila['variacion_monto_pct'], 2, ',', '.') }}%
                                        @else
                                            N/D
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(!is_null($fila['variacion_kw_pct']))
                                            {{ number_format($fila['variacion_kw_pct'], 2, ',', '.') }}%
                                        @else
                                            N/D
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            @if(collect($tablaComparativaMes)->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">No hay datos para los filtros seleccionados.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartComparativoAniosLabels = @json($chartComparativoAniosLabels ?? []);
        const chartComparativoAniosMontos = @json($chartComparativoAniosMontos ?? []);
        const chartComparativoAniosKw = @json($chartComparativoAniosKw ?? []);

        const chartEvolucionMensualLabels = @json($chartEvolucionMensualLabels ?? []);
        const chartEvolucionMontoActual = @json($chartEvolucionMontoActual ?? []);
        const chartEvolucionMontoAnterior = @json($chartEvolucionMontoAnterior ?? []);
        const chartEvolucionKwActual = @json($chartEvolucionKwActual ?? []);
        const chartEvolucionKwAnterior = @json($chartEvolucionKwAnterior ?? []);

        const chartCentroCostoComparativoLabels = @json($chartCentroCostoComparativoLabels ?? []);
        const chartCentroCostoMontoActual = @json($chartCentroCostoMontoActual ?? []);
        const chartCentroCostoMontoAnterior = @json($chartCentroCostoMontoAnterior ?? []);
        const chartCentroCostoKwActual = @json($chartCentroCostoKwActual ?? []);
        const chartCentroCostoKwAnterior = @json($chartCentroCostoKwAnterior ?? []);

        const anioActual = @json($anio);
        const anioAnterior = @json($anioAnterior);

        renderBarMonto(chartComparativoAniosLabels, chartComparativoAniosMontos);
        renderBarKw(chartComparativoAniosLabels, chartComparativoAniosKw);
        renderLineMonto(chartEvolucionMensualLabels, chartEvolucionMontoActual, chartEvolucionMontoAnterior, anioActual, anioAnterior);
        renderLineKw(chartEvolucionMensualLabels, chartEvolucionKwActual, chartEvolucionKwAnterior, anioActual, anioAnterior);
        renderLineCentroCostoComparativo(
            chartCentroCostoComparativoLabels,
            chartCentroCostoMontoActual,
            chartCentroCostoMontoAnterior,
            chartCentroCostoKwActual,
            chartCentroCostoKwAnterior,
            anioActual,
            anioAnterior
        );
    });

    function formatoPesos(valor) {
        return new Intl.NumberFormat('es-CL', {
            style: 'currency',
            currency: 'CLP',
            maximumFractionDigits: 0
        }).format(valor);
    }

    function formatoNumero(valor, decimales = 2) {
        return new Intl.NumberFormat('es-CL', {
            minimumFractionDigits: 0,
            maximumFractionDigits: decimales
        }).format(valor);
    }

    function truncarTexto(texto, max = 22) {
        if (texto === null || texto === undefined) return '';
        const valor = String(texto);
        return valor.length > max ? valor.substring(0, max) + '…' : valor;
    }

    function renderBarMonto(labels, data) {
        const canvas = document.getElementById('chartComparativoMonto');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Monto',
                    data,
                    backgroundColor: 'rgba(92, 26, 195, 0.85)',
                    borderColor: 'rgba(92, 26, 195, 1)',
                    borderWidth: 1,
                    borderRadius: 10,
                    borderSkipped: false,
                    maxBarThickness: 36
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return formatoPesos(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatoPesos(value);
                            }
                        }
                    }
                }
            }
        });
    }

    function renderBarKw(labels, data) {
        const canvas = document.getElementById('chartComparativoKw');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'kW',
                    data,
                    backgroundColor: 'rgba(226, 160, 63, 0.85)',
                    borderColor: 'rgba(226, 160, 63, 1)',
                    borderWidth: 1,
                    borderRadius: 10,
                    borderSkipped: false,
                    maxBarThickness: 36
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return formatoNumero(context.raw, 2) + ' kW';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatoNumero(value, 2) + ' kW';
                            }
                        }
                    }
                }
            }
        });
    }

    function renderLineMonto(labels, actual, anterior, anioActual, anioAnterior) {
        const canvas = document.getElementById('chartEvolucionMonto');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: String(anioActual),
                        data: actual,
                        borderColor: 'rgba(92, 26, 195, 1)',
                        backgroundColor: 'rgba(92, 26, 195, 0.12)',
                        borderWidth: 3,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(92, 26, 195, 1)'
                    },
                    {
                        label: String(anioAnterior),
                        data: anterior,
                        borderColor: 'rgba(27, 85, 226, 1)',
                        backgroundColor: 'rgba(27, 85, 226, 0.12)',
                        borderWidth: 3,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(27, 85, 226, 1)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + formatoPesos(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatoPesos(value);
                            }
                        }
                    }
                }
            }
        });
    }

    function renderLineKw(labels, actual, anterior, anioActual, anioAnterior) {
        const canvas = document.getElementById('chartEvolucionKw');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: String(anioActual),
                        data: actual,
                        borderColor: 'rgba(226, 160, 63, 1)',
                        backgroundColor: 'rgba(226, 160, 63, 0.12)',
                        borderWidth: 3,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(226, 160, 63, 1)'
                    },
                    {
                        label: String(anioAnterior),
                        data: anterior,
                        borderColor: 'rgba(231, 81, 90, 1)',
                        backgroundColor: 'rgba(231, 81, 90, 0.12)',
                        borderWidth: 3,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(231, 81, 90, 1)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + formatoNumero(context.raw, 2) + ' kW';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatoNumero(value, 2) + ' kW';
                            }
                        }
                    }
                }
            }
        });
    }

    function renderLineCentroCostoComparativo(labels, montoActual, montoAnterior, kwActual, kwAnterior, anioActual, anioAnterior) {
        const canvas = document.getElementById('chartCentroCostoComparativo');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Monto ' + anioActual,
                        data: montoActual,
                        borderColor: 'rgba(92, 26, 195, 1)',
                        backgroundColor: 'rgba(92, 26, 195, 0.12)',
                        borderWidth: 3,
                        tension: 0.3,
                        yAxisID: 'y',
                        fill: false,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(92, 26, 195, 1)'
                    },
                    {
                        label: 'Monto ' + anioAnterior,
                        data: montoAnterior,
                        borderColor: 'rgba(27, 85, 226, 1)',
                        backgroundColor: 'rgba(27, 85, 226, 0.12)',
                        borderWidth: 3,
                        tension: 0.3,
                        yAxisID: 'y',
                        fill: false,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(27, 85, 226, 1)'
                    },
                    {
                        label: 'kW ' + anioActual,
                        data: kwActual,
                        borderColor: 'rgba(226, 160, 63, 1)',
                        backgroundColor: 'rgba(226, 160, 63, 0.12)',
                        borderWidth: 3,
                        tension: 0.3,
                        yAxisID: 'y1',
                        fill: false,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(226, 160, 63, 1)'
                    },
                    {
                        label: 'kW ' + anioAnterior,
                        data: kwAnterior,
                        borderColor: 'rgba(231, 81, 90, 1)',
                        backgroundColor: 'rgba(231, 81, 90, 0.12)',
                        borderWidth: 3,
                        tension: 0.3,
                        yAxisID: 'y1',
                        fill: false,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(231, 81, 90, 1)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            title: function(items) {
                                if (!items.length) return '';
                                return labels[items[0].dataIndex] ?? '';
                            },
                            label: function(context) {
                                if (context.dataset.yAxisID === 'y1') {
                                    return context.dataset.label + ': ' + formatoNumero(context.raw, 2) + ' kW';
                                }

                                return context.dataset.label + ': ' + formatoPesos(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 25,
                            callback: function(value) {
                                const label = this.getLabelForValue(value);
                                return truncarTexto(label, 22);
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return formatoPesos(value);
                            }
                        },
                        title: {
                            display: true,
                            text: 'Monto ($)'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            callback: function(value) {
                                return formatoNumero(value, 2) + ' kW';
                            }
                        },
                        title: {
                            display: true,
                            text: 'Consumo (kW)'
                        }
                    }
                }
            }
        });
    }
</script>
@endsection