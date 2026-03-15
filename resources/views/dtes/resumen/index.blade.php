@extends('layouts.theme.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Resumen de Documentos por Tipo y Mes</h3>

    <!-- Tabla de resumen -->
    <div class="table-responsive mb-5">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mes</th>
                    <th>Tipo</th>
                    <th>Total Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resumen as $item)
                    <tr>
                        <td>{{ $item->mes }}</td>
                        <td>{{ ucfirst($item->tipo) }}</td>
                        <td>${{ number_format($item->total_monto, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Gráfico Donut -->
    <div class="mb-5">
        <h4 class="text-center">Distribución Total por Tipo</h4>
        <canvas id="graficoResumen" height="400" width="400"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const datos = @json($resumen);

    // Agrupar por tipo y sumar los montos
    const resumenPorTipo = datos.reduce((acc, curr) => {
        if (!acc[curr.tipo]) {
            acc[curr.tipo] = 0;
        }
        acc[curr.tipo] += curr.total_monto;
        return acc;
    }, {});

    const tipos = Object.keys(resumenPorTipo);
    const montos = Object.values(resumenPorTipo);

    const ctx = document.getElementById('graficoResumen').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: tipos,
            datasets: [{
                label: 'Total por Tipo',
                data: montos,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': $' + context.parsed.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
