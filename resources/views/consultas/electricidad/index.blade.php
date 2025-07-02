@extends('layouts.theme.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/datatables.css')}} ">
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/dt-global_style.css')}} ">


<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/custom_dt_html5.css') }}">

<link href="{{asset('assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/forms/theme-checkbox-radio.css')}}">
<link href="{{asset('assets/css/tables/table-basic.css')}}" rel="stylesheet" type="text/css" />
  
@endsection

@section('title', 'Consumo Electricidad')
@section('title2', 'Indice')

@section('content')

<div class="widget-content widget-content-area br-6 mt-2 mb-2">    
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="d-flex justify-content-between align-items-center">
                <div>                 
                    @include('layouts.theme.partials.breadcrumb')
                </div>
                <div>
                    <h4>Consumo Electricidad</h4>
                </div>

                <div>     
                     <a href="{{ route('dashElectricidad')}}" class="btn btn-outline-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                     </a>
                </div>
            </div>  

            <!-- Tabla de Documentos Tributarios -->
            <div class="row mt-4">
                <div class="col-xl-12">
                    <div class="widget widget-table-one">
                        <table id="html5-extension" class="table table-hover table-striped" style="width:100%">
        <thead class="">
            <tr>             
                <th>IRF</th>
                <th>Numero</th>
                <th>Fecha Recepcion SII</th>
                <th>Periodo</th>
                <th>RutEmisor</th>
                <th>NombreEmisor</th>
                <th>Monto</th>             
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dtesElectricidad as $dte)
                <tr>
               
                    <td>{{ $dte->idRedFlow }}</td>
                    {{-- <td>{{ $dte->NumeroDte }}</td>   --}}
                    <td style="font-weight: bold; text-align: right">
                        <a href="{{$dte->Url}}" class="rounded bs-tooltip" target="_blank" title="Ver PDF">{{ $dte->NumeroDte }}</a>   
                    </td>             
                    <td>{{ $dte->FechaRecepcionSII }}</td> 
                    <td>{{ $dte->Periodo }}</td>
                    <td>{{ $dte->RutEmisor }}</td>
                    <td>{{ $dte->NombreEmisor }}</td>                   
                    <td style="font-weight: bold; text-align: right; font-size: 0.8em">${{ number_format($dte->Monto, 0) }}</td>
                                   
                    <td>{{ $dte->Estado }}</td>

                    {{-- <td class="text-center">
                        <a href="{{ $dte->Url }}" class="btn btn-sm btn-outline-primary" title="Dte">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </a>
                        
                        <a href="{{ route('dtes.detalle.form', ['id' => $dte->id]) }}"
                                 class="btn btn-sm btn-outline-warning" title="Detalle">
                            <i class="fa-solid fa-circle-info"></i>
                        </a>
                    </td>    --}}


                    <td class="text-center">
                        {{-- <a href="{{ $dte->Url }}" class="btn btn-sm btn-outline-primary" title="Dte">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </a> --}}

                        @if (!$dte->detalle)
                            <a href="{{ route('dtes.detalle.form', ['id' => $dte->id]) }}"
                            class="btn btn-sm btn-outline-warning" title="Ingresar Detalle">
                                <i class="fa-solid fa-circle-info"></i>
                            </a>
                        @else
                            <button class="btn btn-sm btn-outline-secondary" title="Detalle ya ingresado" disabled>
                                <i class="fa-solid fa-circle-info"></i>
                            </button>
                        @endif
                    </td>
              
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
