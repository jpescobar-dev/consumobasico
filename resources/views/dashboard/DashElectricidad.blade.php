@extends('layouts.theme.app')

@section('title2', 'Consumo Electricidad')
@section('title', 'Dashboard')

@section('header_actions')
    <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm" title="Volver">Volver</a>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/dt-global_style.css') }}">

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
            height: 380px;
        }

        .chart-wrapper-sm {
            position: relative;
            height: 300px;
        }

        .table thead th {
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            {{-- MÃ©tricas --}}
            <div class="row layout-top-spacing">
                <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
                    <div class="metric-card">
                        <div class="metric-label">Total DTEs</div>
                        <h3 class="metric-value">{{ number_format($metricasDashboard['total_dtes'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
                    <div class="metric-card">
                        <div class="metric-label">Monto total</div>
                        <h3 class="metric-value">${{ number_format($metricasDashboard['total_monto'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
                    <div class="metric-card">
                        <div class="metric-label">Consumo total</div>
                        <h3 class="metric-value">{{ number_format($metricasDashboard['total_consumo_kw'] ?? 0, 2, ',', '.') }} kW</h3>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
                    <div class="metric-card">
                        <div class="metric-label">PerÃ­odos analizados</div>
                        <h3 class="metric-value">{{ number_format($metricasDashboard['cantidad_periodos'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            {{-- GrÃ¡fico principal --}}
            <div class="row">
                <div class="col-xl-12 mb-4">
                    <div class="chart-card">
                        <h5>Consumo mensual por perÃ­odo</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartConsumoPeriodo"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GrÃ¡ficos secundarios --}}
            <div class="row">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="chart-card">
                        <h5>Documentos por tipo</h5>
                        <div class="chart-wrapper-sm">
                            <canvas id="chartDocumentosTipo"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="chart-card">
                        <h5>Consumo por tipo</h5>
                        <div class="chart-wrapper-sm">
                            <canvas id="chartTipoConsumo"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="chart-card">
                        <h5>Consumo por centro de costos</h5>
                        <div class="chart-wrapper-sm">
                            <canvas id="chartCentroCosto"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-12 mb-4">
                    <div class="chart-card">
                        <h5>Consumo por centro financiero</h5>
                        <div class="chart-wrapper-sm">
                            <canvas id="chartCentroFinanciero"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="row layout-top-spacing">
                <div class="col-12">
                    <div class="widget widget-table-one">
                        <h5 class="mt-3 ml-3">Documentos Tributarios</h5>

                        <div class="table-responsive p-3">
                            <table id="dtes-table" class="table table-hover table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="font-weight: bold; font-size: 0.8em;">ID</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">NÃºmero</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Fecha</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Periodo</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Monto</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Rut Emisor</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Consumo</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Cliente</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Cc</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Ccosto</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Cf</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Cfinanciero</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Tipo Consumo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dtesConDetalles as $item)
                                        <tr>
                                            <td style="text-align: center; font-size: 0.8em;">{{ $item->dte_id }}</td>
                                            <td style="text-align: center; font-size: 0.8em;">{{ $item->NumeroDte }}</td>
                                            <td style="text-align: center; font-size: 0.8em;">{{ $item->Fecha }}</td>
                                            <td style="text-align: center; font-size: 0.8em;">{{ $item->Periodo }}</td>
                                            <td style="text-align: right; font-size: 0.8em;">${{ number_format($item->Monto ?? 0, 0, ',', '.') }}</td>
                                            <td style="text-align: center; font-size: 0.8em;">{{ $item->RutEmisor }}</td>
                                            <td style="text-align: right; font-size: 0.8em;">{{ number_format($item->consumo ?? 0, 2, ',', '.') }}</td>
                                            <td style="text-align: center; font-size: 0.8em;">{{ $item->numerocliente }}</td>
                                            <td style="text-align: center; font-size: 0.8em;">{{ $item->ccosto }}</td>
                                            <td style="text-align: center; font-size: 0.8em;">{{ $item->nombre_ccosto }}</td>
                                            <td style="text-align: center; font-size: 0.8em;">{{ $item->cfinanciero }}</td>
                                            <td style="text-align: center; font-size: 0.8em;">{{ $item->nombre_cfinanciero }}</td>
                                            <td style="text-align: center; font-size: 0.8em;">{{ $item->tipoconsumo }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('plugins/table/datatable/datatables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const periodoLabels = @json($chartPeriodoLabels ?? []);
        const montoMensual = @json($chartMontoMensual ?? []);
        const consumoKw = @json($chartConsumoKw ?? []);

        const documentosPorTipo = @json($documentosPorTipo ?? []);
        const consumoPorTipoConsumo = @json($consumoPorTipoConsumo ?? []);
        const consumoxCF = @json($consumoxCF ?? []);

        const centroCostoLabels = @json($chartCentroCostoLabels ?? []);
        const centroCostoSeries = @json($chartCentroCostoSeries ?? []);
        const centroCostoMontos = @json($chartCentroCostoMontos ?? []);

        inicializarDataTable();
        renderGraficoPrincipal(periodoLabels, montoMensual, consumoKw);
        renderGraficoDocumentosPorTipo(documentosPorTipo);
        renderGraficoTipoConsumo(consumoPorTipoConsumo);
        renderGraficoCentroCosto(centroCostoLabels, centroCostoSeries, centroCostoMontos);
        renderGraficoCentroFinanciero(consumoxCF);
    });

    function inicializarDataTable() {
        if (window.jQuery && $.fn.DataTable) {
            $('#dtes-table').DataTable({
                pageLength: 10,
                responsive: true,
                order: [[0, 'desc']],
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    paginate: {
                        previous: "Anterior",
                        next: "Siguiente"
                    },
                    zeroRecords: "No se encontraron registros",
                    infoEmpty: "Sin registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ registros totales)"
                }
            });
        }
    }

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

    function renderGraficoPrincipal(labels, montos, kws) {
        const canvas = document.getElementById('chartConsumoPeriodo');
        if (!canvas || !labels.length) return;

        new Chart(canvas, {
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Consumo mensual en pesos',
                        data: montos,
                        yAxisID: 'yPesos',
                        backgroundColor: 'rgba(27, 85, 226, 0.65)',
                        borderColor: 'rgba(27, 85, 226, 1)',
                        borderWidth: 1
                    },
                    {
                        type: 'line',
                        label: 'Consumo en kW',
                        data: kws,
                        yAxisID: 'yKw',
                        borderColor: 'rgba(231, 81, 90, 1)',
                        backgroundColor: 'rgba(231, 81, 90, 0.15)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: false,
                        pointRadius: 4,
                        pointHoverRadius: 6
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
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y;

                                if (context.dataset.yAxisID === 'yPesos') {
                                    return label + ': ' + formatoPesos(value);
                                }

                                return label + ': ' + formatoNumero(value, 2) + ' kW';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Periodo consumo'
                        }
                    },
                    yPesos: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Consumo mensual en pesos'
                        },
                        ticks: {
                            callback: function(value) {
                                return formatoPesos(value);
                            }
                        }
                    },
                    yKw: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: 'Consumo en kW'
                        },
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

    function renderGraficoDocumentosPorTipo(data) {
        const canvas = document.getElementById('chartDocumentosTipo');
        if (!canvas || !data.length) return;

        const labels = data.map(item => item.TipoDcto ?? 'Sin tipo');
        const values = data.map(item => Number(item.total) || 0);

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Cantidad',
                    data: values,
                    backgroundColor: [
                        '#1b55e2',
                        '#e2a03f',
                        '#e7515a',
                        '#5c1ac3',
                        '#00ab55',
                        '#2196f3',
                        '#ff9800'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + formatoNumero(context.raw, 0);
                            }
                        }
                    }
                }
            }
        });
    }

    function renderGraficoTipoConsumo(data) {
        const canvas = document.getElementById('chartTipoConsumo');
        if (!canvas || !data.length) return;

        const labels = data.map(item => item.tipoconsumo ?? 'Sin tipo');
        const values = data.map(item => Number(item.total_consumo) || 0);

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Consumo',
                    data: values,
                    backgroundColor: [
                        '#5c1ac3',
                        '#e2a03f',
                        '#e7515a',
                        '#1b55e2',
                        '#00ab55',
                        '#607d8b'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + formatoNumero(context.raw, 2) + ' kW';
                            }
                        }
                    }
                }
            }
        });
    }

    function renderGraficoCentroCosto(labels, values, montos) {
        const canvas = document.getElementById('chartCentroCosto');
        if (!canvas || !labels.length) return;

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Consumo por centro de costos',
                    data: values,
                    backgroundColor: [
                        '#1b55e2',
                        '#e2a03f',
                        '#e7515a',
                        '#5c1ac3',
                        '#00ab55',
                        '#607d8b',
                        '#ff9800',
                        '#795548',
                        '#009688',
                        '#673ab7'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const consumo = values[index] ?? 0;
                                const monto = montos[index] ?? 0;

                                return [
                                    context.label,
                                    'Consumo: ' + formatoNumero(consumo, 2) + ' kW',
                                    'Monto: ' + formatoPesos(monto)
                                ];
                            }
                        }
                    }
                }
            }
        });
    }

    function renderGraficoCentroFinanciero(data) {
        const canvas = document.getElementById('chartCentroFinanciero');
        if (!canvas || !data.length) return;

        const labels = data.map(item => item.nombre_cfinanciero ?? item.cfinanciero ?? 'Sin CF');
        const values = data.map(item => Number(item.total_consumo) || 0);

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Consumo total (kW)',
                    data: values,
                    backgroundColor: 'rgba(92, 26, 195, 0.7)',
                    borderColor: 'rgba(92, 26, 195, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Consumo: ' + formatoNumero(context.raw, 2) + ' kW';
                            }
                        }
                    }
                },
                scales: {
                    x: {
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
</script>
@endsection
