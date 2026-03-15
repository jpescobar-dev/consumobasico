@extends('layouts.theme.app')

@section('content')
    @include('layouts.theme.partials.breadcrumb', ['title' => 'Dashboard de Alertas'])

    <div class="container mt-4">
        <div class="row">
            <!-- Promedio de Consumo -->
            <div class="col-md-4 mb-4">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        Promedio General de Consumo Energético
                    </div>
                    <div class="card-body">
                        <h3 class="text-center">{{ number_format($promedioConsumoEnergetico, 2) }} kWh</h3>
                    </div>
                </div>
            </div>

            <!-- Gráfico de DTEs -->
            <div class="col-md-4 mb-4">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        DTEs con y sin Detalle de Consumo
                    </div>
                    <div class="card-body">
                        <div id="chart-dtes"></div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Clientes Fuera de Rango -->
            <div class="col-md-4">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        Clientes con Consumo Fuera del Rango Tolerado (±50%)
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Tipo</th>
                                    <th>Mes</th>
                                    <th>Total Consumo (kWh)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clientesFueraDeRango as $cliente)
                                    <tr>
                                        <td>{{ $cliente->numerocliente }}</td>
                                        <td>{{ $cliente->tipo }}</td>
                                        <td>{{ $cliente->mes }}</td>
                                        <td>{{ number_format($cliente->total_consumo, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No se encontraron clientes fuera de rango.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Cantidad de DTEs',
                    data: [
                        {{ $resumenDtesConYsinDetalle['con_detalle'] }},
                        {{ $resumenDtesConYsinDetalle['sin_detalle'] }}
                    ]
                }],
                xaxis: {
                    categories: ['Con Detalle', 'Sin Detalle']
                },
                colors: ['#28a745', '#dc3545']
            };

            var chart = new ApexCharts(document.querySelector("#chart-dtes"), options);
            chart.render();
        });
    </script>
@endsection
