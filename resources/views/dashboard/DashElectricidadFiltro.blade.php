@extends('layouts.theme.app')

@section('title2', 'Consumo Electricidad')
@section('title', 'Dashboard Filtrado')

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

        .badge-filter {
            background: #eaf1ff;
            color: #1b55e2;
            font-size: 0.8rem;
            padding: 6px 10px;
            border-radius: 20px;
            margin-right: 8px;
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

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    @include('layouts.theme.partials.breadcrumb')
                </div>

                <div>
                    <h4 class="mb-0">Dashboard Consumo Electricidad Filtrado</h4>
                </div>

                <div>
                    <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm" title="Volver">
                        <svg version="1.1" id="Capa_1"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 52.502 52.502"
                            style="enable-background:new 0 0 52.502 52.502;"
                            xml:space="preserve" width="18" height="18">
                            <path d="M51.718,50.857l-1.341-2.252C40.075,31.295,25.975,32.357,22.524,32.917v13.642L0,23.995L22.524,1.644v13.43
                            c0.115,0,0.229-0.001,0.344-0.001c12.517,0,18.294,5.264,18.542,5.496c13.781,11.465,10.839,27.554,10.808,27.715L51.718,50.857z
                            M25.505,30.735c5.799,0,16.479,1.923,24.993,14.345c0.128-4.872-0.896-15.095-10.41-23.012c-0.099-0.088-5.935-5.364-18.533-4.975
                            l-1.03,0.03V6.447L2.832,24.001l17.692,17.724V31.311l0.76-0.188C21.338,31.109,22.947,30.735,25.505,30.735z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="filter-card">
                <form method="GET" action="{{ url()->current() }}">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label class="filter-label">Año</label>
                            <select name="periodo" class="form-control">
                                <option value="">Todos los años</option>
                                @foreach($periodosDisponibles as $item)
                                    <option value="{{ $item }}" @selected($periodo == $item)>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-5 mb-3">
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

                        <div class="col-md-3 mb-3 d-flex">
                            <button type="submit" class="btn btn-primary mr-2">Filtrar</button>
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Limpiar</a>
                        </div>
                    </div>
                </form>

                <div class="mt-2">
                    <span class="badge-filter">Solo DTEs con detalle</span>

                    @if($periodo)
                        <span class="badge-filter">Año: {{ $periodo }}</span>
                    @endif

                    @if($cfinanciero)
                        <span class="badge-filter">Centro Financiero: {{ $cfinanciero }}</span>
                    @endif
                </div>
            </div>

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
                        <div class="metric-label">FAE sin detalle</div>
                        <h3 class="metric-value">{{ number_format($cantidadFaeSinDetalle ?? 0, 0, ',', '.') }}</h3>

                        <div class="mt-2">
                            <a href="{{ $urlFaeSinDetalle }}" class="btn btn-outline-danger btn-sm">
                                Ver FAE sin detalle
                            </a>
                        </div>

                        @if($periodo)
                            <small class="text-muted d-block mt-2">Considera el año {{ $periodo }}</small>
                        @else
                            <small class="text-muted d-block mt-2">Considera todos los años</small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                    <div class="chart-card">
                        <h5>Consumo mensual por tipo de consumo (kW)</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartConsumoMensualTipo"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                    <div class="chart-card">
                        <h5>Monto mensual por tipo de consumo ($)</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartMontoMensualTipo"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="chart-card">
                        <h5>Documentos por tipo</h5>
                        <div class="chart-wrapper-sm">
                            <canvas id="chartDocumentosTipo"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="chart-card">
                        <h5>Consumo por tipo</h5>
                        <div class="chart-wrapper-sm">
                            <canvas id="chartTipoConsumo"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-12 mb-4">
                    <div class="chart-card">
                        <h5>Top 10 consumo por centro de costos (kW)</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartCentroCosto"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-lg-8 col-md-12 mb-4">
                    <div class="chart-card">
                        <h5>Top 10 monto por centro de costos ($)</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartCentroCostoMonto"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-md-12 mb-4">
                    <div class="chart-card">
                        <h5>Consumo por centro financiero</h5>
                        <div class="chart-wrapper-sm">
                            <canvas id="chartCentroFinanciero"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row layout-top-spacing">
                <div class="col-12">
                    <div class="widget widget-table-one">
                        <h5 class="mt-3 ml-3">Documentos Tributarios con detalle</h5>

                        <div class="table-responsive p-3">
                            <table id="dtes-table" class="table table-hover table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="font-weight: bold; font-size: 0.8em;">ID</th>
                                        <th style="font-weight: bold; font-size: 0.8em;">Número</th>
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
        const consumoMensualLabels = @json($chartConsumoMensualLabels ?? []);
        const consumoMensualNormal = @json($chartConsumoMensualNormal ?? []);
        const consumoMensualCalefaccion = @json($chartConsumoMensualCalefaccion ?? []);

        const montoMensualNormal = @json($chartMontoMensualNormal ?? []);
        const montoMensualCalefaccion = @json($chartMontoMensualCalefaccion ?? []);

        const documentosPorTipo = @json($documentosPorTipo ?? []);
        const consumoPorTipoConsumo = @json($consumoPorTipoConsumo ?? []);
        const consumoxCF = @json($consumoxCF ?? []);

        const centroCostoLabels = @json($chartCentroCostoLabels ?? []);
        const centroCostoNormal = @json($chartCentroCostoNormal ?? []);
        const centroCostoCalefaccion = @json($chartCentroCostoCalefaccion ?? []);
        const centroCostoOtros = @json($chartCentroCostoOtros ?? []);
        const centroCostoMontos = @json($chartCentroCostoMontos ?? []);

        const centroCostoMontoNormal = @json($chartCentroCostoMontoNormal ?? []);
        const centroCostoMontoCalefaccion = @json($chartCentroCostoMontoCalefaccion ?? []);
        const centroCostoMontoOtros = @json($chartCentroCostoMontoOtros ?? []);

        inicializarDataTable();

        renderGraficoConsumoMensualTipo(
            consumoMensualLabels,
            consumoMensualNormal,
            consumoMensualCalefaccion
        );

        renderGraficoMontoMensualTipo(
            consumoMensualLabels,
            montoMensualNormal,
            montoMensualCalefaccion
        );

        renderGraficoDocumentosPorTipo(documentosPorTipo);
        renderGraficoTipoConsumo(consumoPorTipoConsumo);

        renderGraficoCentroCosto(
            centroCostoLabels,
            centroCostoNormal,
            centroCostoCalefaccion,
            centroCostoOtros,
            centroCostoMontos
        );

        renderGraficoCentroCostoMonto(
            centroCostoLabels,
            centroCostoMontoNormal,
            centroCostoMontoCalefaccion,
            centroCostoMontoOtros
        );

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

    function truncarTexto(texto, max = 16) {
        if (texto === null || texto === undefined) return '';
        const valor = String(texto);
        return valor.length > max ? valor.substring(0, max) + '…' : valor;
    }

    function renderGraficoConsumoMensualTipo(labels, normalData, calefaccionData) {
        const canvas = document.getElementById('chartConsumoMensualTipo');
        if (!canvas || !labels.length) return;

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Normal',
                        data: normalData,
                        borderColor: 'rgba(92, 26, 195, 1)',
                        backgroundColor: 'rgba(92, 26, 195, 0.15)',
                        borderWidth: 3,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgba(92, 26, 195, 1)',
                        pointBorderColor: 'rgba(92, 26, 195, 1)'
                    },
                    {
                        label: 'Calefacción',
                        data: calefaccionData,
                        borderColor: 'rgba(226, 160, 63, 1)',
                        backgroundColor: 'rgba(226, 160, 63, 0.15)',
                        borderWidth: 3,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgba(226, 160, 63, 1)',
                        pointBorderColor: 'rgba(226, 160, 63, 1)'
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
                    x: {
                        title: {
                            display: true,
                            text: 'Meses del año'
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Consumo (kW)'
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

    function renderGraficoMontoMensualTipo(labels, normalData, calefaccionData) {
        const canvas = document.getElementById('chartMontoMensualTipo');
        if (!canvas || !labels.length) return;

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Normal',
                        data: normalData,
                        backgroundColor: 'rgba(92, 26, 195, 0.85)',
                        borderColor: 'rgba(92, 26, 195, 1)',
                        borderWidth: 1,
                        borderRadius: 10,
                        borderSkipped: false,
                        maxBarThickness: 28
                    },
                    {
                        label: 'Calefacción',
                        data: calefaccionData,
                        backgroundColor: 'rgba(226, 160, 63, 0.85)',
                        borderColor: 'rgba(226, 160, 63, 1)',
                        borderWidth: 1,
                        borderRadius: 10,
                        borderSkipped: false,
                        maxBarThickness: 28
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
                    x: {
                        title: {
                            display: true,
                            text: 'Meses del año'
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Monto prorrateado ($)'
                        },
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

    function renderGraficoCentroCosto(labels, normalData, calefaccionData, otrosData, montos) {
        const canvas = document.getElementById('chartCentroCosto');
        if (!canvas || !labels.length) return;

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Normal',
                        data: normalData,
                        backgroundColor: 'rgba(92, 26, 195, 0.85)',
                        borderColor: 'rgba(92, 26, 195, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Calefacción',
                        data: calefaccionData,
                        backgroundColor: 'rgba(226, 160, 63, 0.85)',
                        borderColor: 'rgba(226, 160, 63, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Otros',
                        data: otrosData,
                        backgroundColor: 'rgba(27, 85, 226, 0.75)',
                        borderColor: 'rgba(27, 85, 226, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
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
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 11
                            },
                            boxWidth: 12
                        }
                    },
                    tooltip: {
                        callbacks: {
                            title: function(items) {
                                if (!items.length) return '';
                                return labels[items[0].dataIndex] ?? '';
                            },
                            label: function(context) {
                                return context.dataset.label + ': ' + formatoNumero(context.raw, 2) + ' kW';
                            },
                            footer: function(items) {
                                if (!items.length) return '';

                                const index = items[0].dataIndex;
                                const totalConsumo =
                                    (Number(normalData[index]) || 0) +
                                    (Number(calefaccionData[index]) || 0) +
                                    (Number(otrosData[index]) || 0);

                                const monto = Number(montos[index]) || 0;

                                return [
                                    'Total consumo: ' + formatoNumero(totalConsumo, 2) + ' kW',
                                    'Monto: ' + formatoPesos(monto)
                                ];
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            autoSkip: false,
                            maxRotation: 35,
                            minRotation: 35,
                            font: {
                                size: 10
                            },
                            callback: function(value) {
                                const label = this.getLabelForValue(value);
                                return truncarTexto(label, 14);
                            }
                        },
                        title: {
                            display: true,
                            text: 'Centros de costo',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Consumo (kW)',
                            font: {
                                size: 11
                            }
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            callback: function(value) {
                                return formatoNumero(value, 2) + ' kW';
                            }
                        }
                    }
                }
            }
        });
    }

    function renderGraficoCentroCostoMonto(labels, normalData, calefaccionData, otrosData) {
        const canvas = document.getElementById('chartCentroCostoMonto');
        if (!canvas || !labels.length) return;

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Normal',
                        data: normalData,
                        backgroundColor: 'rgba(92, 26, 195, 0.85)',
                        borderColor: 'rgba(92, 26, 195, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Calefacción',
                        data: calefaccionData,
                        backgroundColor: 'rgba(226, 160, 63, 0.85)',
                        borderColor: 'rgba(226, 160, 63, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Otros',
                        data: otrosData,
                        backgroundColor: 'rgba(27, 85, 226, 0.75)',
                        borderColor: 'rgba(27, 85, 226, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
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
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 11
                            },
                            boxWidth: 12
                        }
                    },
                    tooltip: {
                        callbacks: {
                            title: function(items) {
                                if (!items.length) return '';
                                return labels[items[0].dataIndex] ?? '';
                            },
                            label: function(context) {
                                return context.dataset.label + ': ' + formatoPesos(context.raw);
                            },
                            footer: function(items) {
                                if (!items.length) return '';

                                const index = items[0].dataIndex;
                                const totalMonto =
                                    (Number(normalData[index]) || 0) +
                                    (Number(calefaccionData[index]) || 0) +
                                    (Number(otrosData[index]) || 0);

                                return 'Total monto: ' + formatoPesos(totalMonto);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            autoSkip: false,
                            maxRotation: 35,
                            minRotation: 35,
                            font: {
                                size: 10
                            },
                            callback: function(value) {
                                const label = this.getLabelForValue(value);
                                return truncarTexto(label, 14);
                            }
                        },
                        title: {
                            display: true,
                            text: 'Centros de costo',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Monto ($)',
                            font: {
                                size: 11
                            }
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            callback: function(value) {
                                return formatoPesos(value);
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