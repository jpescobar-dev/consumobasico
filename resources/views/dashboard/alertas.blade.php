@extends('layouts.theme.app')

@section('content')
@include('layouts.theme.partials.breadcrumb', ['titulo' => 'Alertas de Consumo'])

<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h5>DTEs con y sin detalle</h5>
            <canvas id="dteDetalleChart"></canvas>
        </div>
        <div class="col-md-6">
            <h5>Clientes fuera de rango promedio (±50%)</h5>
            <canvas id="clientesFueraRangoChart"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h5 class="mb-3">Detalle de clientes fuera de rango</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Mes</th>
                            <th>N° Medidor</th>
                            <th>Tipo</th>
                            <th>Total Consumo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientesFueraDeRango as $cliente)
                            <tr>
                                <td>{{ $cliente->mes }}</td>
                                <td>{{ $cliente->numero_medidor }}</td>
                                <td>{{ ucfirst($cliente->tipo) }}</td>
                                <td>{{ number_format($cliente->total_consumo, 2) }}</td>
                            </tr>
                        @endforeach
                        @if ($clientesFueraDeRango->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center">No se encontraron clientes fuera de rango.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 🔵 Gráfico de DTEs con/sin detalle
    const dteDetalleCtx = document.getElementById('dteDetalleChart').getContext('2d');
    new Chart(dteDetalleCtx, {
        type: 'bar',
        data: {
            labels: ['Con Detalle', 'Sin Detalle'],
            datasets: [{
                label: 'Cantidad de DTEs',
                data: [
                    {{ $resumenDtesConYsinDetalle['con_detalle'] }},
                    {{ $resumenDtesConYsinDetalle['sin_detalle'] }}
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // 🔴 Gráfico de consumos fuera del promedio
    const clientesData = @json($clientesFueraDeRango);

    const meses = [...new Set(clientesData.map(item => item.mes))];
    const datasets = {};

    clientesData.forEach(item => {
        if (!datasets[item.numero_medidor]) {
            datasets[item.numero_medidor] = {
                label: `Medidor ${item.numero_medidor} (${item.tipo})`,
                data: Array(meses.length).fill(null),
                fill: false,
                tension: 0.1
            };
        }
        const mesIndex = meses.indexOf(item.mes);
        datasets[item.numero_medidor].data[mesIndex] = item.total_consumo;
    });

    const clientesFueraRangoCtx = document.getElementById('clientesFueraRangoChart').getContext('2d');
    new Chart(clientesFueraRangoCtx, {
        type: 'line',
        data: {
            labels: meses,
            datasets: Object.values(datasets)
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                tooltip: { enabled: true },
                title: {
                    display: true,
                    text: 'Consumos fuera de rango promedio'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
