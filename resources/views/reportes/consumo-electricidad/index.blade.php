@extends('layouts.theme.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/dt-global_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/custom_dt_html5.css') }}">

<link href="{{ asset('assets/css/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/theme-checkbox-radio.css') }}">
<link href="{{ asset('assets/css/tables/table-basic.css') }}" rel="stylesheet" type="text/css" />

<style>
    table.table-hover tbody tr:hover td {
        color: #46576f;
        font-weight: 500;
    }

    .titulo-reporte {
        font-weight: 700;
        font-size: 1rem;
        color: #3b3f5c;
        margin-bottom: 0;
        line-height: 1.1;
    }

    .subtitulo-anio {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1b55e2;
        margin-top: 6px;
        margin-bottom: 10px;
        line-height: 1.1;
    }

    .layout-px-spacing {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    .widget.widget-table-one {
        padding: 10px !important;
    }

    .filtro-card {
        background: #fff;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        padding: 12px;
        margin-top: 16px;
    }

    .reporte-wrap {
        width: 100%;
        overflow: hidden;
    }

    #html5-extension {
        width: 100% !important;
        table-layout: fixed;
        border-collapse: collapse;
        font-family: "Arial Narrow", Arial, Helvetica, sans-serif !important;
        font-size: 11px !important;
    }

    #html5-extension thead th,
    #html5-extension tbody td,
    #html5-extension tfoot th {
        padding: 4px 6px !important;
        vertical-align: middle !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        line-height: 1.15;
    }

    #html5-extension thead th {
        font-size: 10px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    #html5-extension tbody td {
        font-size: 11px !important;
    }

    #html5-extension tfoot th {
        font-size: 11px !important;
        font-weight: 700;
        background: #f1f2f3;
    }

    .cell-truncate {
        display: block;
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .text-num {
        text-align: right;
        font-variant-numeric: tabular-nums;
    }

    .fila-cf td {
        background: #dfe9ff !important;
        font-weight: 700;
        color: #1b55e2;
    }

    .fila-ccosto td {
        background: #f4f7ff !important;
        font-weight: 700;
        color: #3b3f5c;
    }

    .fila-subtotal-cf td {
        background: #eef3ff !important;
        font-weight: 700;
        border-top: 2px solid #1b55e2 !important;
    }

    .fila-detalle td:first-child {
        padding-left: 18px !important;
    }

    .col-cliente   { width: 7.5%; }
    .col-ccosto    { width: 7.5%; }
    .col-tribunal  { width: 15%; }
    .col-tipo      { width: 5%; }
    .col-mes       { width: 4.8%; }
    .col-total     { width: 6.5%; }

    .dataTables_wrapper .dataTables_filter input {
        width: 180px;
        font-size: 12px;
    }

    .dataTables_wrapper .dataTables_length select {
        min-width: 70px;
        font-size: 12px;
    }

    .dt-buttons .btn {
        padding: 4px 8px !important;
        font-size: 11px !important;
    }

    .dataTables_info,
    .dataTables_paginate {
        font-size: 12px !important;
    }

    .td-num {
            text-align: right !important;
            font-variant-numeric: tabular-nums;
        }

    .th-num {
            text-align: center !important;
            font-variant-numeric: tabular-nums;
        }

    @media (max-width: 1600px) {
        #html5-extension {
            font-size: 10px !important;
        }

        #html5-extension thead th,
        #html5-extension tbody td,
        #html5-extension tfoot th {
            padding: 3px 4px !important;
        }

        #html5-extension thead th {
            font-size: 9px !important;
        }

        #html5-extension tbody td,
        #html5-extension tfoot th {
            font-size: 10px !important;
        }        
    }
</style>
@endsection

@section('title', 'Reporte Electricidad')
@section('title2', 'Índice')

@section('content')

<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">Reporte Consumo Electricidad</h4>
                </div>

                <div>
                    <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm" title="Volver">
                        Volver
                    </a>
                </div>
            </div>

            <div class="filtro-card">
                <form method="GET" action="{{ route('reportes.consumo-electricidad.index') }}">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="anio" class="font-weight-bold">Año</label>
                            <select name="anio" id="anio" class="form-control">
                                @foreach($aniosDisponibles as $item)
                                    <option value="{{ $item }}" @selected((string)$anio === (string)$item)>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row mt-4">
                <div class="col-xl-12">
                    <div class="widget widget-table-one">
                        <p class="titulo-reporte">Reporte anual de consumo eléctrico</p>
                        <p class="subtitulo-anio">AÑO {{ $anio }}</p>

                        <div class="reporte-wrap">
                            <table id="html5-extension" class="table table-hover table-striped table-bordered">
                                <colgroup>
                                    <col class="col-cliente">
                                    <col class="col-ccosto">
                                    <col class="col-tribunal">
                                    <col class="col-tipo">
                                    <col class="col-mes">
                                    <col class="col-mes">
                                    <col class="col-mes">
                                    <col class="col-mes">
                                    <col class="col-mes">
                                    <col class="col-mes">
                                    <col class="col-mes">
                                    <col class="col-mes">
                                    <col class="col-mes">
                                    <col class="col-mes">
                                    <col class="col-mes">
                                    <col class="col-mes">
                                    <col class="col-total">
                                </colgroup>

                                <thead>
                                    <tr>
                                        <th class="th-left">N°CLIENTE</th>
                                        <th class="th-left">CCOSTOS</th>
                                        <th class="th-left">TRIBUNAL</th>
                                        <th class="th-left">TIPO</th>
                                        <th class="th-num">ENE</th>
                                        <th class="th-num">FEB</th>
                                        <th class="th-num">MAR</th>
                                        <th class="th-num">ABR</th>
                                        <th class="th-num">MAY</th>
                                        <th class="th-num">JUN</th>
                                        <th class="th-num">JUL</th>
                                        <th class="th-num">AGO</th>
                                        <th class="th-num">SEP</th>
                                        <th class="th-num">OCT</th>
                                        <th class="th-num">NOV</th>
                                        <th class="th-num">DIC</th>
                                        <th class="th-num">TOTAL</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($gruposCentroFinanciero as $grupo)
                                        <tr class="fila-cf">
                                            <td class="td-left">C. FINANCIERO</td>
                                            <td class="td-left">{{ $grupo['cfinanciero'] }}</td>
                                            <td class="td-left" title="{{ $grupo['nombre_cfinanciero'] }}">
                                                <span class="cell-truncate">{{ $grupo['nombre_cfinanciero'] }}</span>
                                            </td>
                                            <td></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                            <td class="td-num"></td>
                                        </tr>

                                        @foreach ($grupo['centros_costo'] as $centro)
                                            <tr class="fila-ccosto">
                                                <td class="td-left">C. COSTO</td>
                                                <td class="td-left">{{ $centro['centro_costo'] }}</td>
                                                <td class="td-left" title="{{ $centro['juzgado'] }}">
                                                    <span class="">{{ $centro['juzgado'] }}</span>
                                                </td>
                                                <td></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                                <td class="td-num"></td>
                                            </tr>

                                            @foreach ($centro['items'] as $item)
                                                <tr class="fila-detalle">
                                                    <td class="td-left" title="{{ $item->numerocliente }}">
                                                        <span class="cell-truncate">{{ $item->numerocliente }}</span>
                                                    </td>

                                                    <td class="td-left" title="{{ $item->centro_costo }}">
                                                        <span class="cell-truncate">{{ $item->centro_costo }}</span>
                                                    </td>

                                                    <td class="td-left" title="{{ $item->juzgado }}">
                                                        <span class="">{{ $item->juzgado }}</span>
                                                    </td>

                                                    <td class="td-left" title="{{ $item->tipo }}">
                                                        <span class="cell-truncate">{{ $item->tipo }}</span>
                                                    </td>

                                                    <td class="td-num">{{ $item->enero > 0 ? number_format($item->enero, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num">{{ $item->febrero > 0 ? number_format($item->febrero, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num">{{ $item->marzo > 0 ? number_format($item->marzo, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num">{{ $item->abril > 0 ? number_format($item->abril, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num">{{ $item->mayo > 0 ? number_format($item->mayo, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num">{{ $item->junio > 0 ? number_format($item->junio, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num">{{ $item->julio > 0 ? number_format($item->julio, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num">{{ $item->agosto > 0 ? number_format($item->agosto, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num">{{ $item->septiembre > 0 ? number_format($item->septiembre, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num">{{ $item->octubre > 0 ? number_format($item->octubre, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num">{{ $item->noviembre > 0 ? number_format($item->noviembre, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num">{{ $item->diciembre > 0 ? number_format($item->diciembre, 0, ',', '.') : '' }}</td>
                                                    <td class="td-num font-weight-bold">{{ number_format($item->total_anual, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach

                                        <tr class="fila-subtotal-cf">
                                            <td class="td-left">SUBTOTAL CF</td>
                                            <td class="td-left">{{ $grupo['cfinanciero'] }}</td>
                                            <td class="td-left" title="{{ $grupo['nombre_cfinanciero'] }}">
                                                <span class="cell-truncate">{{ $grupo['nombre_cfinanciero'] }}</span>
                                            </td>
                                            <td></td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['enero'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['febrero'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['marzo'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['abril'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['mayo'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['junio'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['julio'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['agosto'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['septiembre'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['octubre'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['noviembre'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['diciembre'], 0, ',', '.') }}</td>
                                            <td class="td-num">{{ number_format($grupo['subtotal']['total_anual'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="td-num">TOTAL</th>
                                        <th class="td-num">{{ number_format($totales['enero'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['febrero'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['marzo'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['abril'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['mayo'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['junio'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['julio'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['agosto'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['septiembre'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['octubre'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['noviembre'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['diciembre'], 0, ',', '.') }}</th>
                                        <th class="td-num">{{ number_format($totales['total_anual'], 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script src="{{ asset('plugins/highlight/highlight.pack.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/scrollspyNav.js') }}"></script>

<script>
    const tituloReporte = 'Reporte Consumo Electricidad Año {{ $anio }}';

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
            { extend: 'copy', className: 'btn', title: tituloReporte, footer: true },
            { extend: 'csv', className: 'btn', title: tituloReporte, footer: true },
            { extend: 'excel', className: 'btn', title: tituloReporte, footer: true },
            {
                extend: 'pdf',
                className: 'btn',
                title: tituloReporte,
                footer: true,
                orientation: 'landscape',
                pageSize: 'A3'
            },
            { extend: 'print', className: 'btn', title: tituloReporte, footer: true }
        ],
        oLanguage: {
            oPaginate: {
                sPrevious: '<',
                sNext: '>'
            },
            sInfo: "",
            sSearch: "",
            sSearchPlaceholder: "",
            sLengthMenu: ""
        },
        stripeClasses: [],
        paging: false,
        searching: false,
        ordering: false,
        info: false,
        autoWidth: false,
        scrollX: false,
        lengthChange: false
    });
</script>
@endsection