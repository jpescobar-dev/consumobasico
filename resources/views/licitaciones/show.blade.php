@extends('layouts.theme.app')

@section('title', 'Licitaciones')
@section('title2', 'Ver Detalle')

@section('content')

<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    @include('layouts.theme.partials.breadcrumb')
                </div>
                <div>
                    <h4>Detalle de Licitación</h4>
                </div>
                <div>
                    <a href="{{ route('licitaciones.index') }}" class="btn btn-outline-warning btn-sm">Volver</a>
                </div>
            </div>

            @include('partials.alerts')

           <div class="card shadow-sm  rounded p-4 mt-3">
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group mb-3">
                            <label>Número Licitación</label>
                            <input type="text" class="form-control" value="{{ $licitacion->numero_licitacion }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>Nombre</label>
                            <input type="text" class="form-control" value="{{ $licitacion->nombre }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>Estado</label>
                            <input type="text" class="form-control" value="{{ $licitacion->estado }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>Tipo</label>
                            <input type="text" class="form-control" value="{{ $licitacion->tipo }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>Unidad de Compra</label>
                            <input type="text" class="form-control" value="{{ $licitacion->unidad_compra }}" readonly>
                        </div>

                    </div>
                    <div class="col-md-6">

                        <div class="form-group mb-3">
                            <label>Monto Total Estimado</label>
                            <input type="text" class="form-control" value="{{ number_format($licitacion->monto_total_estimado, 2) }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>N° Ofertas Recibidas</label>
                            <input type="text" class="form-control" value="{{ $licitacion->numero_ofertas_recibidas }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>Fecha de Publicación</label>
                            <input type="text" class="form-control" value="{{ optional($licitacion->fecha_publicacion)->format('d/m/Y H:i') }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>Fecha de Adjudicación</label>
                            <input type="text" class="form-control" value="{{ optional($licitacion->fecha_adjudicacion)->format('d/m/Y H:i') }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>Descripción</label>
                            <textarea class="form-control" rows="3" readonly>{{ $licitacion->descripcion }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
