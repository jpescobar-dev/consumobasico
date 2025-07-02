@extends('layouts.theme.app')

@section('title', 'Dashboard_Electricidad')
@section('title2', 'Documentos Consumo Electricidad')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/dt-global_style.css') }}">
@endsection

@section('content')
<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            <div class="page-header">
                <div class="page-title">
                    <h3>Dashboard Consumo Electricidad</h3>
                </div>
            </div>

            <div class="row layout-top-spacing">
                <!-- Gráfico de Línea -->
                <div class="col-xl-8">
                    <div class="widget widget-chart-one">
                    <h5 class="mt-3 ml-3">Resumen de Montos</h5>
                        <div id="chart1"></div>
                    </div>
                </div>

                <!-- Gráfico Donut -->
                <div class="col-xl-4">
                    <div class="widget widget-chart-two">
                        <h5 class="mt-3 ml-3">Tipo de Consumo</h5>
                        <div id="chart2"></div>
                    </div>
                </div>               
            </div>

            <!-- Tabla de Documentos Tributarios -->
            <div class="row mt-4">
                <div class="col-xl-12">
                    <div class="widget widget-table-one">
                        <h5 class="mt-3 ml-3">Consumo Electricidad</h5>
                        <table id="dtes-table-electricidad" class="table table-hover table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="font-weight: bold; font-size: 0.8em;">Url</th>    
                                    <th style="font-weight: bold; font-size: 0.8em;">Red Flow</th>
                                    <th style="font-weight: bold; font-size: 0.8em;">Fecha SII</th>
                                    <th style="font-weight: bold; font-size: 0.8em;">Tipo</th>
                                    <th style="font-weight: bold; font-size: 0.8em;">Número</th>                                    
                                    <th style="font-weight: bold; font-size: 0.8em;">Rut</th>
                                    <th style="font-weight: bold; font-size: 0.8em;">Emisor</th>
                                    <th style="font-weight: bold; font-size: 0.8em;">Monto</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dtes as $dte)
                                    <tr>
                                    <td>
                                            <a href="{{$dte->Url}}" target="_blank">                                              
                                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                            </a>
                                        </td>
                                        <td style="text-align: center; font-size: 0.8em; width: 10px;">{{ $dte->idRedFlow}}</td>
                                        <td style="text-align: right; font-size: 0.8em">{{ \Carbon\Carbon::parse($dte->FechaRecepcionSII)->format('d/m/Y') }}</td>
                                        <td style="text-align: center; font-size: 0.8em">{{ $dte->TipoDcto }}</td>
                                        <td style="text-align: right; font-size: 0.8em">{{ $dte->NumeroDte }}</td>   
                                        <td style="text-align: right; font-size: 0.8em; width: 100px;">{{$dte->RutEmisor}}</td>
                                        <td style="text-align: left; font-size: 0.8em">{{$dte->NombreEmisor}}</td>
                                        <td style="font-weight: bold; text-align: right; font-size: 0.8em">${{ number_format($dte->Monto, 0) }}</td>
                                        
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
@endsection

@section('scripts')
<script src="{{ asset('plugins/table/datatable/datatables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        try {
            // Convertir JSON a objetos JavaScript válidos de forma segura
            var meses = @json($meses);
            var cantidadDtes = @json($cantidadDtes);
            var totalMontos = @json($totalMontos);
            var documentosPorTipo = @json($documentosPorTipo);

            console.log("Meses:", meses);
            console.log("Cantidad DTEs:", cantidadDtes);
            console.log("Total Montos:", totalMontos);
            console.log("Documentos por Tipo:", documentosPorTipo);

            // Verificar si hay datos antes de inicializar los gráficos
            if (meses.length && cantidadDtes.length && totalMontos.length) {
                renderizarGraficoLinea(meses, cantidadDtes, totalMontos);
            } else {
                console.warn("No hay datos suficientes para el gráfico de línea.");
            }

            if (documentosPorTipo.length) {
                renderizarGraficoDonut(documentosPorTipo);
            } else {
                console.warn("No hay datos suficientes para el gráfico de donut.");
            }

        } catch (error) {
            console.error("Error al cargar los gráficos: ", error);
        }
    });

    // Función para el gráfico de línea
    function renderizarGraficoLinea(meses, cantidadDtes, totalMontos) {
        var options1 = {
            chart: {
                type: 'line',
                height: 365,
                zoom: { enabled: false },
                toolbar: { show: false }
            },
            colors: ['#1b55e2', '#e7515a'],
            dataLabels: { enabled: false },
            stroke: { width: [3, 3], curve: 'smooth' },
            series: [
                { name: "Monto Total ($)", type: "area", data: totalMontos },
                { name: "Cantidad de DTEs", type: "column", data: cantidadDtes }
            ],
            labels: meses,
            xaxis: {
                categories: meses,
            },
            yaxis: [
                { title: { text: "Monto Total ($)" } },
                { opposite: true, title: { text: "Cantidad de DTEs" } }
            ],
        };
        var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
        chart1.render();
    }

    // Función para el gráfico de Donut
    function renderizarGraficoDonut(documentosPorTipo) {
        var tipos = documentosPorTipo.map(doc => doc.TipoDcto);
        var totales = documentosPorTipo.map(doc => doc.total);
        
        var options2 = {
            chart: {
                type: 'donut',
                width: 380
            },
            labels: tipos,
            series: totales,
            colors: ['#5c1ac3', '#e2a03f', '#e7515a', '#e2a03f'],
            legend: {
                position: 'bottom'
            }
        };
        var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
        chart2.render();
    }

    // Inicializar DataTables después de que todo haya cargado
    $(document).ready(function() {
        $('#dtes-table-electricidad').DataTable({
            "oLanguage": {
                "oPaginate": { 
                    "sPrevious": "<", 
                    "sNext": ">" 
                },
                "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                "sSearch": "Buscar:",
                "sLengthMenu": "Mostrar _MENU_ registros"
            },
            "order": [[1, "desc"]],
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7
        });
    });
</script>
@endsection  
