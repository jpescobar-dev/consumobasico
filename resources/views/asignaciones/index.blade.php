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

@section('title', 'Asignaciones')
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
                    <h4>Asignaciones Presupuestarias</h4>
                </div>

                <div>
                    <!-- Contenido derecho -->
                    <a href="{{ route('asignaciones.create') }}" class="btn btn-outline-primary btn-sm">
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
                                <th>Asignación</th>
                                <th>Item</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th class="text-center" style="width: 180px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($asignaciones as $asignacion)
                                <tr>
                                    <td>{{ $asignacion->asignacion }}</td>
                                    <td>{{ $asignacion->item }}</td>
                                    <td>{{ Str::limit($asignacion->nombre, 60) }}</td>
                                    <td>{{ Str::limit($asignacion->descripcion, 100) }}</td>


                                     <td class="text-center">
                                        <div class="d-inline-flex align-items-center" style="gap: 2px;">
                                            <a href="{{ route('asignaciones.show', $asignacion) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>  
                                            <a href="{{ route('asignaciones.edit', $asignacion) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('asignaciones.destroy', $asignacion) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta asignación?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>                         
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay asignaciones registradas.</td>
                                </tr>
                            @endforelse
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
