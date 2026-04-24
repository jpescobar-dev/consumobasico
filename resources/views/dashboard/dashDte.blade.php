@extends('layouts.theme.app')

@section('title2', 'Documentos Tributarios')
@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/dt-global_style.css') }}">
@endsection

@section('content')
<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div id="content" class="main-content">
        
        <div class="layout-px-spacing">  
             <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4>Dashboard DTES</h4>
                </div>

                <div>
                    <!-- Contenido derecho -->
                     <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm" title="Volver">
                        <svg version="1.1" id="Capa_1" 
                            xmlns="http://www.w3.org/2000/svg"   
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 52.502 52.502"     
                            style="enable-background:new 0 0 52.502 52.502;" 
                            xml:space="preserve">
                            <path d="M51.718,50.857l-1.341-2.252C40.075,31.295,25.975,32.357,22.524,32.917v13.642L0,23.995L22.524,1.644v13.43
                            c0.115,0,0.229-0.001,0.344-0.001c12.517,0,18.294,5.264,18.542,5.496c13.781,11.465,10.839,27.554,10.808,27.715L51.718,50.857z
                            M25.505,30.735c5.799,0,16.479,1.923,24.993,14.345c0.128-4.872-0.896-15.095-10.41-23.012c-0.099-0.088-5.935-5.364-18.533-4.975
                            l-1.03,0.03V6.447L2.832,24.001l17.692,17.724V31.311l0.76-0.188C21.338,31.109,22.947,30.735,25.505,30.735z"/>
                        </svg>                 
                    </a>
                </div>
            </div>  

    <div class="row">
        <!-- Gráfico combinado -->
        <div class="col-md-12">
            <div id="chart-combined"></div>
        </div>
    </div>
</div>

<!-- Cargar ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    try {
        // Convertir JSON a objetos JavaScript válidos
        var meses = JSON.parse('{!! json_encode($meses) !!}');
        var cantidadDtes = JSON.parse('{!! json_encode($cantidadDtes) !!}');
        var totalMontos = JSON.parse('{!! json_encode($totalMontos) !!}');

        console.log("Meses:", meses);
        console.log("Cantidad DTEs:", cantidadDtes);
        console.log("Total Montos:", totalMontos);

        // Manejo de valores vacíos
        if (!Array.isArray(meses) || meses.length === 0) meses = ["Sin datos"];
        if (!Array.isArray(cantidadDtes) || cantidadDtes.length === 0) cantidadDtes = [0];
        if (!Array.isArray(totalMontos) || totalMontos.length === 0) totalMontos = [0];

        // Configuración del gráfico combinado
        var optionsCombined = {
            chart: {
                type: 'line',
                height: 400,
                stacked: false
            },
            stroke: {
                width: [0, 4] // Ancho de las líneas (0 para barras, 4 para línea)
            },
            series: [
                {
                    name: "Monto Total",
                    type: "area",
                    data: totalMontos
                },
                {
                    name: "Cantidad de Facturas",
                    type: "column",
                    data: cantidadDtes
                }
            ],
            xaxis: {
                categories: meses
            },
            yaxis: [
                {
                    title: {
                        text: "Monto Total ($)", // Eje principal (izquierdo)
                    }
                },
                {
                    opposite: true,
                    title: {
                        text: "Cantidad de Facturas" // Eje secundario (derecho)
                    }
                }
            ],
            colors: ['#1b55e2', '#e7515a'],
            tooltip: {
                shared: true,
                intersect: false
            },
            legend: {
                position: "top"
            }
        };

        // Renderizar el gráfico
        var chartCombined = new ApexCharts(document.querySelector("#chart-combined"), optionsCombined);
        chartCombined.render();

    } catch (error) {
        console.error("Error al cargar los gráficos: ", error);
    }
});
</script>
@endsection
