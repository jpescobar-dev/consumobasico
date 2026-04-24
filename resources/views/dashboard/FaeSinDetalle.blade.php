@extends('layouts.theme.app')

@section('title2', 'Consumo Electricidad')
@section('title', 'FAE sin detalle')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/table/datatable/dt-global_style.css') }}">
@endsection

@section('content')
<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-0">FAE sin detalle</h4>
                </div>

                <div>
                    <a href="{{ route('dashboard.electricidad.filtro', array_filter(['periodo' => $periodo])) }}"
                       class="btn btn-outline-primary btn-sm">
                        Volver al dashboard
                    </a>
                </div>
            </div>

            <div class="row layout-top-spacing mb-3">
                <div class="col-md-4">
                    <div class="metric-card" style="border: 1px solid #e0e6ed; border-radius: 10px; background: #fff; padding: 18px;">
                        <div style="font-size: 0.85rem; color: #888ea8; margin-bottom: 8px;">Cantidad FAE sin detalle</div>
                        <h3 style="margin: 0; font-weight: 700;">{{ number_format($cantidadFaeSinDetalle, 0, ',', '.') }}</h3>

                        @if($periodo)
                            <small class="text-muted d-block mt-2">Período: {{ $periodo }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row layout-top-spacing">
                <div class="col-12">
                    <div class="widget widget-table-one">
                        <h5 class="mt-3 ml-3">Listado de FAE sin detalle</h5>

                        <div class="table-responsive p-3">
                            <table id="fae-sin-detalle-table" class="table table-hover table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Número</th>
                                        <th>Fecha</th>
                                        <th>Período</th>
                                        <th>Monto</th>
                                        <th>Rut Emisor</th>
                                        <th>Tipo Dcto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faeSinDetalle as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->NumeroDte }}</td>
                                            <td>{{ $item->Fecha }}</td>
                                            <td>{{ $item->periodo_consumo }}</td>
                                            <td>${{ number_format($item->Monto ?? 0, 0, ',', '.') }}</td>
                                            <td>{{ $item->RutEmisor }}</td>
                                            <td>{{ $item->TipoDcto }}</td>
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
</div>
@endsection

@section('scripts')
<script src="{{ asset('plugins/table/datatable/datatables.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && $.fn.DataTable) {
            $('#fae-sin-detalle-table').DataTable({
                pageLength: 10,
                responsive: true,
                order: [[0, 'desc']],
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    paginate: {
                        previous: "Anterior",
                        next: "Siguiente"
                    },
                    zeroRecords: "No se encontraron registros",
                    infoEmpty: "Sin registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ registros totales)"
                }
            });
        }
    });
</script>
@endsection