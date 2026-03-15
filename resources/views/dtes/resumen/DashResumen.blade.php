@extends('layouts.theme.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Resumen por Mes y Tipo</h3>

    <!-- Tabla -->
    {{-- <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mes</th>
                <th>Tipo</th>
                <th>Total Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resumen as $row)
                <tr>
                    <td>{{ $row->mes }}</td>
                    <td>{{ ucfirst($row->tipo) }}</td>
                    <td>${{ number_format($row->total_monto, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}

    <!-- Gráfico -->
    <canvas id="graficoResumen" height="120"></canvas>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const datos = @json($resumen);

    // Agrupar por tipo
    const tipos = [...new Set(datos.map(d => d.tipo))];
    const meses = [...new Set(datos.map(d => d.mes))];

    const datasets = tipos.map(tipo => {
        return {
            label: tipo,
            data: meses.map(mes => {
                const item = datos.find(d => d.mes === mes && d.tipo === tipo);
                return item ? item.total_monto : 0;
            }),
            borderWidth: 1
        };
    });

    const ctx = document.getElementById('graficoResumen').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
