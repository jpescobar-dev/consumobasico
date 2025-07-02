@extends('layouts.theme.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/datatables.css')}} ">
<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/dt-global_style.css')}} ">


<link rel="stylesheet" type="text/css" href="{{asset('plugins/table/datatable/custom_dt_html5.css') }}">

<link href="{{asset('assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/forms/theme-checkbox-radio.css')}}">
<link href="{{asset('assets/css/tables/table-basic.css')}}" rel="stylesheet" type="text/css" />
  
@endsection

@section('title', 'Iniciativas')
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
                    <h4>Listado Iniciativas</h4>
                </div>

                <div>
                    <!-- Contenido derecho -->
                    <a href="{{ route('proyectos.create') }}" class="btn btn-outline-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="84" height="84" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>                    
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
                    <th>ID</th>          
                    <th>Código</th> 
                    <th>C.Financiero</th>
                    <th>Nombre</th>
                    <th>Descripción</th>                  
                    <th>Fecha Inicio</th>
                    <th>Fecha Término</th>
                    <th>Estado</th>
                    <th>Avance</th>
                    <th>Monto Estimado</th>                    
                    <th>Monto Asignado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proyectos as $proyecto)
                    <tr>    
                        <td>{{ $proyecto->id }}</td>  
                        <td>{{ $proyecto->codigo }}</td>
                        <td>{{ $proyecto->centroFinanciero->cfinanciero ?? 'N/A' }}</td>                
                        <td>{{ $proyecto->proyecto }}</td>
                        <td>{{ $proyecto->descripcion }}</td>                        
                        <td>{{ $proyecto->fecha_inicio }}</td>
                        <td>{{ $proyecto->fecha_termino }}</td>
                        <td>{{ $proyecto->estado->nombre ?? 'N/A' }}</td>
                        <td>{{ $proyecto->avance }}%</td>
                        <td>{{ number_format($proyecto->monto_estimado, 0) }}</td>
                        <td>{{ number_format($proyecto->monto_asignado, 0) }}</td>                   
                        
                        
                        <td>   
                            <a href="{{ route('proyectos.show', $proyecto) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('proyectos.edit', $proyecto) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>


                            <form action="{{ route('proyectos.destroy', $proyecto) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este Proyecto?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
