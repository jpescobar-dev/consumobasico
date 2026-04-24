@extends('layouts.theme.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/dt-global_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/custom_dt_html5.css') }}">
<link href="{{ asset('assets/css/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/theme-checkbox-radio.css') }}">
<link href="{{ asset('assets/css/tables/table-basic.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('title', 'CDPs')
@section('title2', 'Índice')

@section('content')

@include('partials.alerts')

<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div class="">
        <a href="{{ route('cdps.create') }}" class="btn btn-primary btn-sm mt-2 mb-2">
            <i class="fas fa-plus"></i> Nuevo CDP
        </a>
    </div>

    <table id="html5-extension" class="table table-hover table-striped" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>N° CDP</th>
                <th>Fecha</th>
                <th>Proceso SGF</th>
                <th>Centro Financiero</th>
                <th>Centro Costo</th>
                <th>Catálogo</th>
                <th>Proyecto</th>
                <th>Moneda</th>
                <th>Monto Total</th>
                <th>Estado</th>
                <th>Docs</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cdps as $cdp)
                <tr>
                    <td>{{ $cdp->id }}</td>
                    <td>{{ $cdp->num_cdp }}</td>
                    <td>{{ optional($cdp->fecha_cdp)->format('d-m-Y') }}</td>
                    <td>{{ $cdp->proceso_sgf }}</td>
                    <td>
                        {{ $cdp->cfinanciero_id }}
                        @if(optional($cdp->cfinanciero)->nombre)
                            - {{ $cdp->cfinanciero->nombre }}
                        @endif
                    </td>
                    <td>{{ $cdp->ccosto }}</td>
                    <td>{{ $cdp->catalogo }}</td>
                    <td>{{ optional($cdp->proyecto)->proyecto }}</td>
                    <td>{{ $cdp->moneda }}</td>
                    <td>{{ number_format((float) $cdp->monto_total_impto_incluido, 0, ',', '.') }}</td>
                    <td>{{ optional($cdp->estado)->estado }}</td>
                    <td>{{ $cdp->documentos->count() }}</td>
                    <td class="text-center">
                        <a href="{{ route('cdps.show', $cdp) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>

                        <a href="{{ route('cdps.edit', $cdp) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('cdps.destroy', $cdp) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este CDP?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
                sPrevious: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"...></svg>',
                sNext: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"...></svg>'
            },
            sInfo: "Mostrando página _PAGE_ de _PAGES_",
            sSearch: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"...></svg>',
            sSearchPlaceholder: "Buscar...",
            sLengthMenu: "Resultados : _MENU_"
        },
        stripeClasses: [],
        lengthMenu: [10, 20, 50],
        pageLength: 10
    });
</script>
@endsection


