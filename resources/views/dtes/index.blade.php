@extends('layouts.theme.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/scrollspyNav.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/theme-checkbox-radio.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tables/table-basic.css') }}">
        <style>
        /* Cambia color del texto en toda la fila al hacer hover */
        table.table-hover tbody tr:hover td {
            color: #46576f; /* Azul Bootstrap 5 */
            font-weight: 500; /* Ligero realce de peso */
        }
    </style>
@endsection

@section('title', 'DTEs')
@section('title2', 'Índice')

@section('content')

<div class="widget-content widget-content-area br-6 mt-2 mb-2">    
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="d-flex justify-content-between align-items-center">
                <div>                 
                    @include('layouts.theme.partials.breadcrumb')
                </div>
                <div>
                    <h4>Documentos Tributarios Electrónicos</h4>
                </div>

                <div>     
                     <a href="{{ route('excel.import-form')}}" class="btn btn-outline-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                </a>
                </div>
            </div>  

            <!-- Tabla de Documentos Tributarios -->
            <div class="row mt-4">
                <div class="col-xl-12">
                    <div class="widget widget-table-one">
                        <table id="html5-extension" class="table table-hover table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    {{-- <th>Url</th>     --}}
                                    <th>Red Flow</th>
                                    <th>Fecha SII</th>
                                    <th>Tipo</th>
                                    <th>Número</th>                                    
                                    <th>Rut</th>
                                    <th>Emisor</th>                                
                                    <th>Estado</th>
                                    <th>Egreso</th>    
                                    <th>Monto</th>                               
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dtes as $dte)
                                    <tr>                                       
                                        <td class="text-center" style="font-size: 0.8em;">{{ $dte->idRedFlow }}</td>
                                        <td class="text-right" style="font-size: 0.8em;">{{ \Carbon\Carbon::parse($dte->FechaRecepcionSII)->format('d/m/Y') }}</td>
                                        <td class="text-center" style="font-size: 0.8em;">{{ $dte->TipoDcto }}</td>
                                        <td class="text-right" style="font-size: 0.8em;">
                                            <a href="{{ $dte->Url }}" class="rounded bs-tooltip" target="_blank" title="Ver PDF">
                                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                            </a>  {{ $dte->NumeroDte }}                                            
                                        </td>   
                                        <td class="text-right" style="font-size: 0.8em;">{{ $dte->RutEmisor }}</td>
                                        <td class="text-left" style="font-size: 0.8em;">{{ $dte->NombreEmisor }}</td>                                        
                                        <td class="text-left" style="font-size: 0.8em;">{{ $dte->Estado }}</td>
                                        <td class="text-left" style="font-size: 0.8em;">{{ $dte->Egreso }}</td>
                                        <td class="text-right font-weight-bold" style="font-size: 0.8em;">${{ number_format($dte->Monto, 0) }}</td>                                        
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
    <script src="{{ asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/table/datatable/button-ext/jszip.min.js') }}"></script>    
    <script src="{{ asset('plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/highlight/highlight.pack.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>  
    <script src="{{ asset('assets/js/scrollspyNav.js') }}"></script>

    <script>
        $('#html5-extension').DataTable({
            dom: `
                <'row mb-3'
                    <'col-md-3'l>
                    <'col-md-6 text-center'B>
                    <'col-md-3'f>
                >
                <'row'
                    <'col-md-12'tr>
                >
                <'row'
                    <'col-md-5'i>
                    <'col-md-7'p>
                >`,
            buttons: [
                { extend: 'copy', className: 'btn' },
                { extend: 'csv', className: 'btn' },
                { extend: 'excel', className: 'btn' },
                { extend: 'print', className: 'btn' }
            ],
            oLanguage: {
                oPaginate: {
                    sPrevious: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">...</svg>',
                    sNext: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">...</svg>'
                },
                sInfo: "Mostrando página _PAGE_ de _PAGES_",
                sSearch: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">...</svg>',
                sSearchPlaceholder: "Buscar...",
                sLengthMenu: "Resultados : _MENU_"
            },
            stripeClasses: [],
            lengthMenu: [10, 20, 50],
            pageLength: 10
        });
    </script>    
@endsection
